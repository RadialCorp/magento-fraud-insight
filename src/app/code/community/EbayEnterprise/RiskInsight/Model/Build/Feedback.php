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

class EbayEnterprise_RiskInsight_Model_Build_Feedback
	implements EbayEnterprise_RiskInsight_Model_Build_IFeedback
{
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var EbayEnterprise_RiskInsight_Model_IPayload */
	protected $_feedback;
	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helper;
	/** @var EbayEnterprise_RiskInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'order' => Mage_Sales_Model_Order
	 */
	public function __construct(array $initParams)
	{
		list($this->_order, $this->_feedback, $this->_helper, $this->_config) = $this->_checkTypes(
			$initParams['order'],
			$this->_nullCoalesce($initParams, 'feedback', Mage::getModel('ebayenterprise_riskinsight/feedback')),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('ebayenterprise_riskinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('ebayenterprise_riskinsight/config'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  EbayEnterprise_RiskInsight_Helper_Data
	 * @param  EbayEnterprise_RiskInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		EbayEnterprise_RiskInsight_Model_IPayload $feedback,
		EbayEnterprise_RiskInsight_Helper_Data $helper,
		EbayEnterprise_RiskInsight_Helper_Config $config
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
	 * @see EbayEnterprise_RiskInsight_Model_Build_IRequest::build()
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
			->setChargeBackFlag(static::CHARGE_BACK_FLAG)
			->setChargeBackFlagDescription(static::CHARGE_BACK_FLAG_DESCRIPTION);
		return $this;
	}
}
