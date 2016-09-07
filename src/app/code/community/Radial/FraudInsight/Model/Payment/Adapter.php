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

class Radial_FraudInsight_Model_Payment_Adapter
	implements Radial_FraudInsight_Model_Payment_IAdapter
{
	/** @var Radial_FraudInsight_Model_Payment_Adapter_IType */
	protected $_adapter;
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;
	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'helper' => Radial_FraudInsight_Helper_Data
	 *                          - 'config' => Radial_FraudInsight_Helper_Config
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_order, $this->_helper, $this->_config) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'order', $initParams['order']),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('radial_fraudinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('radial_fraudinsight/config'))
		);
		$this->_adapter = $this->_getPaymentAdapter($this->_getAdapters());
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Helper_Data
	 * @param  Radial_FraudInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Helper_Data $helper,
		Radial_FraudInsight_Helper_Config $config
	) {
		return array($order, $helper, $config);
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

	public function getAdapter()
	{
		return $this->_adapter;
	}

	/**
	 * @return array
	 */
	protected function _getAdapters()
	{
		return $this->_config->getPaymentAdapterMap();
	}

	/**
	 * @return Radial_FraudInsight_Model_Payment_Adapter_IType | null
	 */
	protected function _getPaymentAdapter()
	{
		return Mage::getModel($this->_getAdapterModel(), array('order' => $this->_order));
	}

	/**
	 * @return string
	 */
	protected function _getAdapterModel()
	{
		$adapters = $this->_getAdapters();
		$method = $this->_getMethod();
		return (isset($adapters[$method]) && $adapters[$method])
			? $adapters[$method] : static::DEFAULT_ADAPTER;
	}

	/**
	 * @return string
	 */
	public function _getMethod()
	{
		$payment = $this->_order->getPayment();
		return $this->_helper->isGiftCardPayment($this->_order, $payment)
			? static::GIFT_CARD_PAYMENT_METHOD : $payment->getMethod();
	}
}
