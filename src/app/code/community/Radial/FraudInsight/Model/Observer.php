<?php
/**
 * Copyright (c) 2013-2016 Radial Commerce Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2013-2016 Radial Commerce Inc. (http://www.radial.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Radial_FraudInsight_Model_Observer
{
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;
	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams optional keys:
	 *                          - 'helper' => Radial_FraudInsight_Helper_Data
	 *                          - 'config' => Radial_FraudInsight_Helper_Config
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_helper, $this->_config) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('radial_fraudinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('radial_fraudinsight/config'))
		);
	}

	/**
	 * Type checks for self::__construct $initParams
	 *
	 * @param  Radial_FraudInsight_Helper_Data
	 * @param  Radial_FraudInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Radial_FraudInsight_Helper_Data $helper,
		Radial_FraudInsight_Helper_Config $config
	) {
		return array($helper, $config);
	}
	/**
	 * Return the value at field in array if it exists. Otherwise, use the
	 * default value.
	 *
	 * @param array      $arr
	 * @param string|int $field Valid array key
	 * @param mixed      $default
	 * @return mixed
	 */
	protected function _nullCoalesce(array $arr, $field, $default)
	{
		return isset($arr[$field]) ? $arr[$field] : $default;
	}

	/**
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _handleProcessOrder(Mage_Sales_Model_Order $order)
	{
		Mage::getModel('radial_fraudinsight/risk_fraud', array(
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
	 * @codeCoverageIgnore
	 */
	protected function _logWarning($logMessage)
	{
		Mage::log($logMessage, Zend_Log::WARN);
		return $this;
	}

	/**
	 * Consume the event 'sales_model_service_quote_submit_after'. Pass the Mage_Sales_Model_Order object
	 * from the event down to the 'radial_fraudinsight/risk_fraud' instance. Invoke the process
	 * method on the 'radial_fraudinsight/risk_fraud' instance.
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
	 * Entry point for the CRONJOB 'radial_fraudinsight_process_risk_fraud'.
	 *
	 * @return self
	 */
	public function detectFraudulentOrders()
	{
		if ($this->_config->isEnabled()) {
			Mage::getModel('radial_fraudinsight/risk_order', array(
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
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _processOrderFeedback(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Model_Risk_Insight $insight
	)
	{
		Mage::getModel('radial_fraudinsight/send_feedback', array(
			'order' => $order,
			'insight' => $insight,
		))->send();
		return $this;
	}

	/**
	 * Entry point for the CRONJOB 'radial_fraudinsight_resend_fail_feedback_request'.
	 *
	 * @return self
	 */
	public function resendFeedbacks()
	{
		if ($this->_config->isEnabled()) {
			Mage::getModel('radial_fraudinsight/cron_feedback', array(
				'helper' => $this->_helper,
			))->process();
		}
		return $this;
	}
}
