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

class EbayEnterprise_RiskInsight_Model_Risk_Order
{
	/** @var EbayEnterprise_RiskInsight_Helper_Data $_helper */
	protected $_helper;
	/** @var EbayEnterprise_RiskInsight_Helper_Config $_config*/
	protected $_config;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'helper' => EbayEnterprise_RiskInsight_Helper_Data
	 *                          - 'config' => EbayEnterprise_RiskInsight_Helper_Config
	 */
	public function __construct(array $initParams=array())
	{

		list($this->_helper, $this->_config) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('ebayenterprise_riskinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('ebayenterprise_riskinsight/config'))
		);
	}
	/**
	 * Type hinting for self::__construct $initParams
	 * @param  EbayEnterprise_RiskInsight_Helper_Data $helper
	 * @param  EbayEnterprise_RiskInsight_Helper_Config $config
	 * @return array
	 */
	protected function _checkTypes(
		EbayEnterprise_RiskInsight_Helper_Data $helper,
		EbayEnterprise_RiskInsight_Helper_Config $config,
	) {
		return array($helper, $config);
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
	 * Get new empty request payload
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _getNewEmptyRequest()
	{
		return Mage::getModel('ebayenterprise_riskinsight/request');
	}
	/**
	 * Get new empty response payload
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _getNewEmptyResponse()
	{
		return Mage::getModel('ebayenterprise_riskinsight/response');
	}
	/**
	 * Get new API config object.
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload $request
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload $response
	 * @return EbayEnterprise_RiskInsight_Model_IConfig
	 */
	protected function _setupApiConfig(
		EbayEnterprise_RiskInsight_Model_IPayload $request,
		EbayEnterprise_RiskInsight_Model_IPayload $response
	)
	{
		return Mage::getModel('ebayenterprise_riskinsight/config', array(
			'api_key' => $this->_config->getApiKey(),
			'host' => $this->_config->getApiHostname(),
			'store_id' => $this->_config->getStoreId(),
			'request' => $request,
			'response' => $response,
		));
	}
	/**
	 * Get new API object.
	 * @return EbayEnterprise_RiskInsight_Model_IApi
	 */
	protected function _getApi(EbayEnterprise_RiskInsight_Model_IConfig $config)
	{
		return Mage::getModel('ebayenterprise_riskinsight/api', $config);
	}
	/**
	 * Get a collection of risk insight, send UCP Service Request, base on the response
	 * either change the order status to what is configured from the response code or
	 * simply log message and do nothing.
	 * @return self
	 */
	public function process()
	{
		$insightCollection = $this->_helper->getRiskInsightCollection();
		$incrementIds = $this->_getOrderIncementIds($insightCollection);
		$orderCollection = $this->_helper->getOrderCollectionByIncrementIds($incrementIds);
		foreach ($collection as $insight) {
			$order = $orderCollection->getItemByColumnValue('increment_id', $incrementIds);
			if ($order) {
				$fufillRequest = $this->_buildRequestFromOrder($this->_getNewEmptyRequest(), $order);
				$apiConfig = $this->_setupApiConfig($fufillRequest, $this->_getNewEmptyResponse());
				$api = $this->_getApi($apiConfig);
			}
		}
		return $this;
	}

	/**
	 * Get all order increment id from a passed in collection of risk insight instance.
	 * @param  EbayEnterprise_RiskInsight_Model_Resource_Risk_Insight_Collection $collections
	 * @return array
	 */
	protected function _getOrderIncementIds(EbayEnterprise_RiskInsight_Model_Resource_Risk_Insight_Collection $collections)
	{
		$incrementIds = array();
		foreach ($collections as $insight) {
			$incrementIds[] = $insight->getOrderIncrementId();
		}
		return $incrementIds;
	}
}
