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

class Radial_FraudInsight_Model_Send_Feedback
	extends Radial_FraudInsight_Model_Abstract
	implements Radial_FraudInsight_Model_Send_IFeedback
{
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Radial_FraudInsight_Model_Risk_Insight */
	protected $_insight;
	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams Must have these keys:
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'insight' => Radial_FraudInsight_Model_Risk_Insight
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_order, $this->_insight, $this->_config) = $this->_checkTypes(
			$initParams['order'],
			$initParams['insight'],
			$this->_nullCoalesce($initParams, 'config', Mage::helper('radial_fraudinsight/config'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Radial_FraudInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Radial_FraudInsight_Helper_Config $config
	) {
		return array($order, $insight, $config);
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
	 * @see Radial_FraudInsight_Model_Send_IFeedback::send()
	 */
	public function send()
	{
		return $this->_sendFeedback();
	}

	/**
	 * Build the feedback request payload using the sales/order object in the self::$_order class property,
	 * prepare the API accordingly, send the feedback request and get the response. If the response is valid
	 * passed it down to the self::_processFeedbackResponse() protected method.
	 *
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _sendFeedback()
	{
		$feedbackRequest = $this->_buildFeedbackRequestFromOrder($this->_order, $this->_getNewEmptyFeedbackRequest());
		$apiConfig = $this->_setupApiConfig($feedbackRequest, $this->_getNewEmptyFeedbackResponse());
		$response = $this->_sendFeedbackRequest($this->_getApi($apiConfig));
		if ($response) {
			$this->_processFeedbackResponse($response, $this->_insight);
		}
		return $response;
	}

	/**
	 * Get a new empty feedback request payload
	 *
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _getNewEmptyFeedbackRequest()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback');
	}

	/**
	 * Get a new empty feedback response payload
	 *
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _getNewEmptyFeedbackResponse()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback_Response');
	}

	/**
	 * Get a new API config object.
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
	 * Get a new API object.
	 *
	 * @return Radial_FraudInsight_Sdk_IApi
	 */
	protected function _getApi(Radial_FraudInsight_Sdk_IConfig $config)
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Api', $config);
	}

	/**
	 * Try to send feedback request if no exception was thrown get the response payload and return it.
	 * Otherwise if an exception was thrown while sending the request simply log it.
	 *
	 * @param  Radial_FraudInsight_Sdk_IApi
	 * @return Radial_FraudInsight_Sdk_IPayload | null
	 */
	protected function _sendFeedbackRequest(Radial_FraudInsight_Sdk_IApi $api)
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
	 * Build the passed in feedback request object using the passed in order object.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _buildFeedbackRequestFromOrder(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Sdk_IPayload $feedback
	)
	{
		return Mage::getModel('radial_fraudinsight/build_feedback', array(
			'order' => $order,
			'feedback' => $feedback,
		))->build();
	}

	/**
	 * Process the feedback response payload.
	 *
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @return self
	 */
	protected function _processFeedbackResponse(
		Radial_FraudInsight_Sdk_IPayload $response,
		Radial_FraudInsight_Model_Risk_Insight $insight
	)
	{
		Mage::getModel('radial_fraudinsight/process_feedback_response', array(
			'response' => $response,
			'insight' => $insight,
		))->process();
		return $this;
	}
}
