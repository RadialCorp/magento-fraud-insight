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

class EbayEnterprise_RiskInsight_Model_Send_Feedback
	implements EbayEnterprise_RiskInsight_Model_Send_IFeedback
{
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var EbayEnterprise_RiskInsight_Model_Risk_Insight */
	protected $_insight;
	/** @var EbayEnterprise_RiskInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams Must have these keys:
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'insight' => EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_order, $this->_insight, $this->_config) = $this->_checkTypes(
			$initParams['order'],
			$initParams['insight'],
			$this->_nullCoalesce($initParams, 'config', Mage::helper('ebayenterprise_riskinsight/config'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  EbayEnterprise_RiskInsight_Model_Risk_Insight
	 * @param  EbayEnterprise_RiskInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		EbayEnterprise_RiskInsight_Model_Risk_Insight $insight,
		EbayEnterprise_RiskInsight_Helper_Config $config
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
	 * @see EbayEnterprise_RiskInsight_Model_Send_IFeedback::send()
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
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
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
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _getNewEmptyFeedbackRequest()
	{
		return Mage::getModel('ebayenterprise_riskinsight/feedback');
	}

	/**
	 * Get a new empty feedback response payload
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _getNewEmptyFeedbackResponse()
	{
		return Mage::getModel('ebayenterprise_riskinsight/feedback_response');
	}

	/**
	 * Get a new API config object.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
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
	 * Get a new API object.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IApi
	 */
	protected function _getApi(EbayEnterprise_RiskInsight_Model_IConfig $config)
	{
		return Mage::getModel('ebayenterprise_riskinsight/api', $config);
	}

	/**
	 * Try to send feedback request if no exception was thrown get the response payload and return it.
	 * Otherwise if an exception was thrown while sending the request simply log it.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_IApi
	 * @return EbayEnterprise_RiskInsight_Model_IPayload | null
	 */
	protected function _sendFeedbackRequest(EbayEnterprise_RiskInsight_Model_IApi $api)
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
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _buildFeedbackRequestFromOrder(
		Mage_Sales_Model_Order $order,
		EbayEnterprise_RiskInsight_Model_IPayload $feedback
	)
	{
		return Mage::getModel('ebayenterprise_riskinsight/build_feedback', array(
			'order' => $order,
			'feedback' => $feedback,
		))->build();
	}

	/**
	 * Process the feedback response payload.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  EbayEnterprise_RiskInsight_Model_Risk_Insight
	 * @return self
	 */
	protected function _processFeedbackResponse(
		EbayEnterprise_RiskInsight_Model_IPayload $response,
		EbayEnterprise_RiskInsight_Model_Risk_Insight $insight
	)
	{
		Mage::getModel('ebayenterprise_riskinsight/process_feedback_response', array(
			'response' => $response,
			'insight' => $insight,
		))->process();
		return $this;
	}
}
