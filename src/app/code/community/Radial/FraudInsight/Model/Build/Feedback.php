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

class Radial_FraudInsight_Model_Build_Feedback
	extends Radial_FraudInsight_Model_Abstract
	implements Radial_FraudInsight_Model_Build_IFeedback
{
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Radial_FraudInsight_Sdk_IPayload */
	protected $_feedback;
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;
	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'order' => Mage_Sales_Model_Order
	 */
	public function __construct(array $initParams)
	{
		list($this->_order, $this->_feedback, $this->_helper, $this->_config) = $this->_checkTypes(
			$initParams['order'],
			$this->_nullCoalesce($initParams, 'feedback', $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback')),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('radial_fraudinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('radial_fraudinsight/config'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Helper_Data
	 * @param  Radial_FraudInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Sdk_IPayload $feedback,
		Radial_FraudInsight_Helper_Data $helper,
		Radial_FraudInsight_Helper_Config $config
	) {
		return array($order, $feedback, $helper, $config);
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
	 * @see Radial_FraudInsight_Model_Build_IRequest::build()
	 */
	public function build()
	{
		$this->_buildFeedback();
		return $this->_feedback;
	}

	/**
	 * Populate the risk insight feedback payload.
	 *
	 * @return self
	 */
	protected function _buildFeedback()
	{
		$this->_feedback->setPrimaryLangId($this->_helper->getLanguageCode())
			->setOrderId($this->_order->getIncrementId())
			->setStoreId($this->_config->getStoreId())
			->setActionTaken($this->_order->getState())
			->setActionTakenDescription($this->_order->getStatus())
			->setChargeBackCode(static::CHARGE_BACK_CODE)
			->setChargeBackFlagDescription(static::CHARGE_BACK_FLAG_DESCRIPTION);
		return $this;
	}
}
