<?php
/**
 * Copyright (c) 2015 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

/**
 * @codeCoverageIgnore
 */
class EbayEnterprise_RiskInsight_Model_Observer
{
	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helper;
	/** @var EbayEnterprise_RiskInsight_Helper_Config */
	protected $_config;

	public function __construct()
	{
		$this->_helper = Mage::helper('ebayenterprise_riskinsight');
		$this->_config = Mage::helper('ebayenterprise_riskinsight/config');
	}

	/**
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _handleProcessOrder(Mage_Sales_Model_Order $order)
	{
		Mage::getModel('ebayenterprise_riskinsight/risk_fraud', array(
			'order' => $order,
			'helper' => $this->_helper,
		))->process();
		return $this;
	}

	/**
	 * @param  mixed
	 * @return bool
	 */
	protected function _isValidOrder($order=null)
	{
		return ($order && $order instanceof Mage_Sales_Model_Order);
	}

	/**
	 * @param  string
	 * @return self
	 */
	protected function _logWarning($logMessage)
	{
		Mage::log($logMessage, Zend_Log::WARN);
		return $this;
	}

	/**
	 * Consume the event 'sales_model_service_quote_submit_after'. Pass the Mage_Sales_Model_Order object
	 * from the event down to the 'ebayenterprise_riskinsight/risk_fraud' instance. Invoke the process
	 * method on the 'ebayenterprise_riskinsight/risk_fraud' instance.
	 *
	 * @param  Varien_Event_Observer
	 * @return self
	 */
	public function handleSalesModelServiceQuoteSubmitAfter(Varien_Event_Observer $observer)
	{
		$order = $observer->getEvent()->getOrder();
		if ($this->_isValidOrder($order)) {
			$this->_handleProcessOrder($order);
		} else {
			$logMessage = sprintf('[%s] No sales/order instance was found.', __CLASS__);
			$this->_logWarning($logMessage);
		}
		return $this;
	}

	/**
	 * @param  array
	 * @return self
	 */
	protected function _handleProcessMultipleOrders(array $orders)
	{
		foreach ($orders as $index => $order) {
			if ($this->_isValidOrder($order)) {
				$this->_handleProcessOrder($order);
			} else {
				$logMessage = sprintf('[%s] Multi-shipping order index %d was not a valid instance of sales/order class.', __CLASS__, $index);
				$this->_logWarning($logMessage);
			}
		}
		return $this;
	}

	/**
	 * Handle multi-shipping orders.
	 *
	 * @param  Varien_Event_Observer
	 * @return self
	 */
	public function handleCheckoutSubmitAllAfter(Varien_Event_Observer $observer)
	{
		$orders = (array) $observer->getEvent()->getOrders();
		if (!empty($orders)) {
			$this->_handleProcessMultipleOrders($orders);
		} else {
			$logMessage = sprintf('[%s] No multi-shipping sales/order instances was found.', __CLASS__);
			$this->_logWarning($logMessage);
		}
		return $this;
	}

	/**
	 * Entry point for the CRONJOB 'ebayenterprise_riskinsight_process_risk_fraud'.
	 *
	 * @return self
	 */
	public function detectFraudulentOrders()
	{
		if ($this->_config->isEnabled()) {
			Mage::getModel('ebayenterprise_riskinsight/risk_order', array(
				'helper' => $this->_helper,
			))->process();
		}
		return $this;
	}

	/**
	 * Consume the event 'sales_order_save_after'. Get the sales/order object
	 * from the event. Validate we have a valid sales/order object, then pass it
	 * down to self::_processOrderFeedback() protected method. If we don't have
	 * a valid sales/order object we simply log a warning message.
	 *
	 * @param  Varien_Event_Observer
	 * @return self
	 */
	public function handleSalesOrderSaveAfter(Varien_Event_Observer $observer)
	{
		$order = $observer->getEvent()->getOrder();
		if ($this->_isValidOrder($order)) {
			$this->_handleOrderFeedback($order);
		} else {
			$logMessage = sprintf('[%s] No sales/order instance was found to send risk feedback request.', __CLASS__);
			$this->_logWarning($logMessage);
		}
		return $this;
	}

	/**
	 * Get the associated risk insight object for the passed in sales/order object.
	 * Determine if the sales/order and risk insight objects are in a state to send
	 * feedback request, if so, we simply passed the sales/order object and risk insight object
	 * to the self::_processOrderFeedback() protected method.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _handleOrderFeedback(Mage_Sales_Model_Order $order)
	{
		$insight = $this->_helper->getRiskInsight($order);
		if ($this->_helper->canHandleFeedback($order, $insight)) {
			$this->_processOrderFeedback($order, $insight);
		}
		return $this;
	}

	/**
	 * If we have reached this point that mean we have an order that is either canceled or completed.
	 * Also, we have a risk insight record where the risk insight request has already been sent,
	 * there were no previously successful feedback request sent, and the fail attempt feedback request
	 * counter is less than the configured threshold. Therefore, we simply send the feedback request.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _processOrderFeedback(
		Mage_Sales_Model_Order $order,
		EbayEnterprise_RiskInsight_Model_Risk_Insight $insight
	)
	{
		Mage::getModel('ebayenterprise_riskinsight/send_feedback', array(
			'order' => $order,
			'insight' => $insight,
		))->send();
		return $this;
	}

	/**
	 * Entry point for the CRONJOB 'ebayenterprise_riskinsight_resend_fail_feedback_request'.
	 *
	 * @return self
	 */
	public function resendFeedbacks()
	{
		if ($this->_config->isEnabled()) {
			Mage::getModel('ebayenterprise_riskinsight/cron_feedback', array(
				'helper' => $this->_helper,
			))->process();
		}
		return $this;
	}
}
