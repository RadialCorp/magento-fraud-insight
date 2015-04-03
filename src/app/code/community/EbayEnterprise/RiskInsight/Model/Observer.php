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
	 * @param  Mage_Sales_Model_Order $order
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
}
