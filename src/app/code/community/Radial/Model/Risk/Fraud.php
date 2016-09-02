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

class Radial_FraudInsight_Model_Risk_Fraud
	implements Radial_FraudInsight_Model_Risk_IFraud
{
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'helper' => Radial_FraudInsight_Helper_Data
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_order, $this->_helper) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'order', $initParams['order']),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('radial_fraudinsight'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Helper_Data
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Helper_Data $helper
	) {
		return array($order, $helper);
	}

	/**
	 * Return the value at field in array if it exists. Otherwise, use the default value.
	 *
	 * @param  array
	 * @param  string | int $field Valid array key
	 * @param  mixed
	 * @return mixed
	 */
	protected function _nullCoalesce(array $arr, $field, $default)
	{
		return isset($arr[$field]) ? $arr[$field] : $default;
	}

	/**
	 * Get new risk instance loaded by passed in order increment id.
	 *
	 * @param  string
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _getNewRiskInsightByOrderId($orderIncrementId)
	{
		return Mage::getModel('radial_fraudinsight/risk_insight')
			->load($orderIncrementId, 'order_increment_id');
	}

	/**
	 * Get all risk insight data.
	 *
	 * @param  string
	 * @return array
	 */
	protected function _getRiskInsightData($orderIncrementId)
	{
		return array(
			'order_increment_id' => $orderIncrementId,
			'http_headers' => json_encode($this->_helper->getHeaderData()),
			'is_request_sent' => 0,
		);
	}

	/**
	 * Check if we have a valid order increment id
	 *
	 * @param  string
	 * @return bool
	 */
	protected function _canAddNewRiskInsightData($orderIncrementId)
	{
		if ($orderIncrementId === '') {
			$logMessage = sprintf('[%s] Received empty customer order id.', __CLASS__);
			Mage::log($logMessage, Zend_Log::WARN);
			return false;
		}
		return true;
	}

	public function process()
	{
		$orderIncrementId = trim($this->_order->getIncrementId());
		if ($this->_canAddNewRiskInsightData($orderIncrementId)) {
			$this->_getNewRiskInsightByOrderId($orderIncrementId)
				->addData($this->_getRiskInsightData($orderIncrementId))
				->save();
		}
		return $this;
	}
}
