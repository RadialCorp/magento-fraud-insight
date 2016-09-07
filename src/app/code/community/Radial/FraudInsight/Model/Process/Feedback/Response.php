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

class Radial_FraudInsight_Model_Process_Feedback_Response
	extends Radial_FraudInsight_Model_Abstract
	implements Radial_FraudInsight_Model_Process_Feedback_IResponse
{
	/** @var Radial_FraudInsight_Sdk_IPayload */
	protected $_response;
	/** @var Radial_FraudInsight_Model_Risk_Insight */
	protected $_insight;
	/** @var Radial_FraudInsight_Sdk_Helper */
	protected $_sdkHelper;

	/**
	 * @param array $initParams Must have these keys:
	 *                          - 'response' => Radial_FraudInsight_Sdk_IPayload
	 *                          - 'insight' => Radial_FraudInsight_Model_Risk_Insight
	 */
	public function __construct(array $initParams)
	{
		list($this->_response, $this->_insight, $this->_sdkHelper) = $this->_checkTypes(
			$initParams['response'],
			$initParams['insight'],
			$this->_nullCoalesce($initParams, 'sdk_helper', $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Helper'))
		);
		$this->_checkRiskInsight();
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Radial_FraudInsight_Sdk_Helper
	 * @return array
	 */
	protected function _checkTypes(
		Radial_FraudInsight_Sdk_IPayload $response,
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Radial_FraudInsight_Sdk_Helper $sdkHelper
	) {
		return array($response, $insight, $sdkHelper);
	}

	/**
	 * @see Radial_FraudInsight_Model_Process_IResponse::process()
	 */
	public function process()
	{
		if ($this->_response instanceof Radial_FraudInsight_Sdk_Feedback_Response) {
			$this->_updateFeedback();
		} elseif ($this->_response instanceof Radial_FraudInsight_Sdk_Error) {
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
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _incrementFeedbackAttemptCount()
	{
		return $this->_insight->setFeedbackSentAttemptCount(
			(int) $this->_insight->getFeedbackSentAttemptCount() + 1
		);
	}
}
