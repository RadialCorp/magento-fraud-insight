<?php
/**
 * Copyright (c) 2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

class EbayEnterprise_RiskInsight_Model_Risk_Fraud
{
	/** @var Mage_Sales_Model_Order $_order */
	protected $_order;
	/** @var EbayEnterprise_RiskInsight_Helper_Data $_helper */
	protected $_helper;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'helper' => EbayEnterprise_RiskInsight_Helper_Data
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_order, $this->_helper) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'order', $initParams['order']),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('ebayenterprise_riskinsight'))
		);
	}
	/**
	 * Type hinting for self::__construct $initParams
	 * @param  Mage_Sales_Model_Order $order
	 * @param  EbayEnterprise_RiskInsight_Helper_Data $helper
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		EbayEnterprise_RiskInsight_Helper_Data $helper
	) {
		return array($order, $helper);
	}
	/**
	 * Return the value at field in array if it exists. Otherwise, use the default value.
	 * @param  array $arr
	 * @param  string | int $field Valid array key
	 * @param  mixed $default
	 * @return mixed
	 */
	protected function _nullCoalesce(array $arr, $field, $default)
	{
		return isset($arr[$field]) ? $arr[$field] : $default;
	}
	/**
	 * Get new risk instance loaded by passed in order increment id.
	 * @param  string $orderIncrementId
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	protected function _getNewRiskInsightByOrderId($orderIncrementId)
	{
		return Mage::getModel('ebayenterprise_riskinsight/risk_insight')
			->load($orderIncrementId, 'order_increment_id');
	}
	/**
	 * Get all risk insight data.
	 * @param  string $orderIncrementId
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
	 * @param  string $orderIncrementId
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
	/**
	 * Adding newly created order to the risk fraud database table
	 * including header information.
	 * @return self
	 */
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
