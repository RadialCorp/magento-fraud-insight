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

class Radial_FraudInsight_Model_Risk_Order
	extends Radial_FraudInsight_Model_Abstract
	implements Radial_FraudInsight_Model_Risk_IOrder
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
	 * Type hinting for self::__construct $initParams
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
	 * Get new empty request payload
	 *
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _getNewEmptyRequest()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Request');
	}

	/**
	 * Get new empty response payload
	 *
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _getNewEmptyResponse()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Response');
	}

	/**
	 * Get new API config object.
	 *
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @return Radial_FraudInsight_Sdk_IConfig
	 */
	protected function _setupApiConfig(
		Radial_FraudInsight_Sdk_IPayload $request,
		Radial_FraudInsight_Sdk_IPayload $response
	)
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Config', array(
			'api_key' => $this->_config->getApiKey(),
			'host' => $this->_config->getApiHostname(),
			'store_id' => $this->_config->getStoreId(),
			'request' => $request,
			'response' => $response,
		));
	}

	/**
	 * Get new API object.
	 *
	 * @param  Radial_FraudInsight_Sdk_IConfig
	 * @return Radial_FraudInsight_Sdk_IApi
	 * @codeCoverageIgnore
	 */
	protected function _getApi(Radial_FraudInsight_Sdk_IConfig $config)
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Api', $config);
	}

	public function process()
	{
		$this->_processRiskOrderCollection($this->_helper->getRiskInsightCollection());
		return $this;
	}

	public function processRiskOrder(
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order
	)
	{
		$fufillRequest = $this->_buildRequestFromOrder($this->_getNewEmptyRequest(), $insight, $order);
		$apiConfig = $this->_setupApiConfig($fufillRequest, $this->_getNewEmptyResponse());
		$response = $this->_sendRequest($this->_getApi($apiConfig));
		if ($response) {
			$this->_processResponse($response, $insight, $order);
		}
	}

	/**
	 * @param  Radial_FraudInsight_Model_Resource_Risk_Insight_Collection
	 * @return self
	 */
	protected function _processRiskOrderCollection(Radial_FraudInsight_Model_Resource_Risk_Insight_Collection $insightCollection)
	{
		$orderCollection = $this->_helper->getOrderCollectionByIncrementIds($insightCollection->getColumnValues('order_increment_id'));
		foreach ($insightCollection as $insight) {
			$order = $orderCollection->getItemByColumnValue('increment_id', $insight->getOrderIncrementId());
			if ($order) {
				$this->processRiskOrder($insight, $order);
			}
		}
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_IApi
	 * @return Radial_FraudInsight_Sdk_IPayload | null
	 */
	protected function _sendRequest(Radial_FraudInsight_Sdk_IApi $api)
	{
		$response = null;
		try {
			$api->send();
			$response = $api->getResponseBody();
		} catch (Exception $e) {
			$logMessage = sprintf('[%s] The following error has occurred while sending request: %s', __CLASS__, $e->getMessage());
			Mage::log($logMessage, Zend_Log::WARN);
			Mage::logException($e);
		}
		return $response;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _processResponse(
		Radial_FraudInsight_Sdk_IPayload $response,
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order
	)
	{
		return Mage::getModel('radial_fraudinsight/process_response', array(
			'response' => $response,
			'insight' => $insight,
			'order' => $order,
		))->process();
	}

	/**
	 * Build the passed in request object using the passed in order and insight object.
	 *
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _buildRequestFromOrder(
		Radial_FraudInsight_Sdk_IPayload $request,
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order
	)
	{
		return Mage::getModel('radial_fraudinsight/build_request', array(
			'request' => $request,
			'insight' => $insight,
			'order' => $order,
		))->build();
	}
}
