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

class Radial_FraudInsight_Model_Process_Response
	implements Radial_FraudInsight_Model_Process_IResponse
{
	/** @var Radial_FraudInsight_Sdk_IPayload */
	protected $_response;
	/** @var Radial_FraudInsight_Model_Risk_Insight */
	protected $_insight;
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'response' => Radial_FraudInsight_Sdk_IPayload
	 *                          - 'insight' => Radial_FraudInsight_Model_Risk_Insight
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'config' => Radial_FraudInsight_Helper_Config
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_response, $this->_insight, $this->_order, $this->_config) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'response', $initParams['response']),
			$this->_nullCoalesce($initParams, 'insight', $initParams['insight']),
			$this->_nullCoalesce($initParams, 'order', $initParams['order']),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('radial_fraudinsight/config'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Radial_FraudInsight_Sdk_IPayload $response,
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Helper_Config $config
	) {
		return array($response, $insight, $order, $config);
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

	public function process()
	{
		if ($this->_response instanceof Radial_FraudInsight_Sdk_Response) {
            // Updating the Risk Insight record with response data.
            $this->_updateRiskInsight();

			switch ($this->_response->getResponseReasonCode()) {
				case static::RESPONSE_CODE_HIGH:
					$this->_processHighRiskOrder();
					break;
				case static::RESPONSE_CODE_MEDIUM:
					$this->_processMediumRiskOrder();
					break;
				case static::RESPONSE_CODE_LOW:
					$this->_processLowRiskOrder();
					break;
				case static::RESPONSE_CODE_UNKNOWN:
					$this->_processUnknownRiskOrder();
					break;
			}
		} elseif ($this->_response instanceof Radial_FraudInsight_Sdk_Error) {
			$this->_processError();
		}
		return $this;
	}

	/**
	 * @param  string
	 * @return self
	 */
	protected function _processAction($action)
	{
		switch ($action) {
			case Radial_FraudInsight_Model_System_Config_Source_Responseaction::HOLD_FOR_REVIEW:
				$this->_order->hold();
				$this->_updateOrder(
					static::STATUS_RISK_REVIEW,
					Mage_Sales_Model_Order::STATE_HOLDED
				);
				break;
			case Radial_FraudInsight_Model_System_Config_Source_Responseaction::CANCEL:
				$this->_order->cancel();
				$this->_updateOrder(
					static::STATUS_RISK_CANCELED,
					Mage_Sales_Model_Order::STATE_CANCELED
				);
				break;
		}
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _processHighRiskOrder()
	{
		$this->_processAction($this->_config->getHighResponseAction());
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _processMediumRiskOrder()
	{
		$this->_processAction($this->_config->getMediumResponseAction());
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _processLowRiskOrder()
	{
		$this->_processAction($this->_config->getLowResponseAction());
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _processUnknownRiskOrder()
	{
		$this->_processAction($this->_config->getUnknownResponseAction());
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _processError()
	{
		$logMessage = sprintf('[%s] Response Error Code: %s', __CLASS__, $this->_response->getErrorCode());
		Mage::log($logMessage, Zend_Log::WARN);
		$logMessage = sprintf('[%s] Response Error Description: %s', __CLASS__, $this->_response->getErrorDescription());
		Mage::log($logMessage, Zend_Log::WARN);
		return $this;
	}

	/**
	 * Update the order status and state.
	 *
	 * @param  string
	 * @param  string
	 * @return self
	 */
	protected function _updateOrder($status, $state)
	{
		$this->_order->setStatus($status)
			->setState($state, false)
			->save();
		return $this;
	}

	/**
	 * Update the risk insight record with response data and setting
	 * the 'is_request_sent' field to 1.
	 *
	 * @return self
	 */
	protected function _updateRiskInsight()
	{
		$this->_insight->setResponseReasonCode($this->_response->getResponseReasonCode())
			->setResponseReasonCodeDescription($this->_response->getResponseReasonCodeDescription())
			->setIsRequestSent(1)
			->save();
		return $this;
	}
}
