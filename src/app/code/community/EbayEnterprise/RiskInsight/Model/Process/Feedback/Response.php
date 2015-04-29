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

class EbayEnterprise_RiskInsight_Model_Process_Feedback_Response
	extends EbayEnterprise_RiskInsight_Model_Abstract
	implements EbayEnterprise_RiskInsight_Model_Process_Feedback_IResponse
{
	/** @var EbayEnterprise_RiskInsight_Sdk_IPayload */
	protected $_response;
	/** @var EbayEnterprise_RiskInsight_Model_Risk_Insight */
	protected $_insight;
	/** @var EbayEnterprise_RiskInsight_Sdk_Helper */
	protected $_sdkHelper;

	/**
	 * @param array $initParams Must have these keys:
	 *                          - 'response' => EbayEnterprise_RiskInsight_Sdk_IPayload
	 *                          - 'insight' => EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	public function __construct(array $initParams)
	{
		list($this->_response, $this->_insight, $this->_sdkHelper) = $this->_checkTypes(
			$initParams['response'],
			$initParams['insight'],
			$this->_nullCoalesce($initParams, 'sdk_helper', $this->_getNewSdkInstance('EbayEnterprise_RiskInsight_Sdk_Helper'))
		);
		$this->_checkRiskInsight();
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  EbayEnterprise_RiskInsight_Sdk_IPayload
	 * @param  EbayEnterprise_RiskInsight_Model_Risk_Insight
	 * @param  EbayEnterprise_RiskInsight_Sdk_Helper
	 * @return array
	 */
	protected function _checkTypes(
		EbayEnterprise_RiskInsight_Sdk_IPayload $response,
		EbayEnterprise_RiskInsight_Model_Risk_Insight $insight,
		EbayEnterprise_RiskInsight_Sdk_Helper $sdkHelper
	) {
		return array($response, $insight, $sdkHelper);
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Model_Process_IResponse::process()
	 */
	public function process()
	{
		if ($this->_response instanceof EbayEnterprise_RiskInsight_Sdk_Feedback_Response) {
			$this->_updateFeedback();
		} elseif ($this->_response instanceof EbayEnterprise_RiskInsight_Sdk_Error) {
			$this->_processFeedbackError();
		}
		return $this;
	}

	/**
	 * Check if the risk insight model is loaded with data, if not load it
	 * with data from the database.
	 *
	 * @return self
	 */
	protected function _checkRiskInsight()
	{
		$orderIncrementId = $this->_response->getOrderId();
		if (!$this->_isLoaded() && $orderIncrementId) {
			$this->_insight->load($orderIncrementId, 'order_increment_id');
		}
		return $this;
	}

	/**
	 * Check if the risk insight object is loaded with data from the database.
	 *
	 * @return bool
	 */
	protected function _isLoaded()
	{
		return ($this->_insight->getId() > 0);
	}

	/**
	 * Increment the fail feedback request attempt counter and log response messages.
	 *
	 * @return self
	 */
	protected function _processFeedbackError()
	{
		$this->_incrementRequetAttempt()
			->_logResponse();
		return $this;
	}

	/**
	 * Log the error code and error code description from the response payload.
	 *
	 * @return self
	 */
	protected function _logResponse()
	{
		$logMessage = sprintf('[%s] Response Error Code: %s', __CLASS__, $this->_response->getErrorCode());
		Mage::log($logMessage, Zend_Log::WARN);
		$logMessage = sprintf('[%s] Response Error Description: %s', __CLASS__, $this->_response->getErrorDescription());
		Mage::log($logMessage, Zend_Log::WARN);
		return $this;
	}

	/**
	 * Determine if the risk insight is loaded with valid data from the database.
	 * if so, simply increment the feedback attempt count field and save the data
	 * back to the database.
	 *
	 * @return self
	 */
	protected function _incrementRequetAttempt()
	{
		if ($this->_isLoaded()) {
			$this->_incrementFeedbackAttemptCount()->save();
		}
		return $this;
	}

	/**
	 * Update the risk insight feedback data with the response payload data.
	 *
	 * @return self
	 */
	protected function _updateFeedback()
	{
		if ($this->_isLoaded()) {
			$this->_incrementFeedbackAttemptCount()
				->setIsFeedbackSent(1)
				->setActionTakenAcknowledgement($this->_sdkHelper->convertStringToBoolean($this->_response->getActionTakenAcknowledgement()))
				->setChargeBackAcknowledgement($this->_sdkHelper->convertStringToBoolean($this->_response->getChargeBackAcknowledgement()))
				->save();
		}
		return $this;
	}

	/**
	 * Increment the feedback sent attempt count.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	protected function _incrementFeedbackAttemptCount()
	{
		return $this->_insight->setFeedbackSentAttemptCount(
			(int) $this->_insight->getFeedbackSentAttemptCount() + 1
		);
	}
}
