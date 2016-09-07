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

/**
 * @codeCoverageIgnore
 */
class Radial_FraudInsight_Helper_Config
{
	const ENABLED = 'radial_fraudinsight/fraud_insight/enabled';
	const STORE_ID = 'radial_fraudinsight/fraud_insight/store_id';
	const API_HOSTNAME = 'radial_fraudinsight/fraud_insight/hostname';
	const API_KEY = 'radial_fraudinsight/fraud_insight/key';
	const API_TIMEOUT = 'radial_fraudinsight/fraud_insight/timeout';
	const DEBUG = 'radial_fraudinsight/fraud_insight/debug';
	const ORDER_SOURCE = 'radial_fraudinsight/fraud_insight/order_source';
	const HIGH_RESPONSE_ACTION = 'radial_fraudinsight/fraud_insight/high_action';
	const MEDIUM_RESPONSE_ACTION = 'radial_fraudinsight/fraud_insight/medium_action';
	const LOW_RESPONSE_ACTION = 'radial_fraudinsight/fraud_insight/low_action';
	const UNKNOWN_RESPONSE_ACTION = 'radial_fraudinsight/fraud_insight/unknown_action';
	const LANGUAGE_CODE = 'radial_fraudinsight/fraud_insight/language_code';
	const CARD_TYPE_MAP = 'radial_fraudinsight/fraud_insight/card_type_map';
	const SHIPPING_METHOD_MAP = 'radial_fraudinsight/fraud_insight/shipping_method_map';
	const PAYMENT_ADAPTER_MAP = 'radial_fraudinsight/fraud_insight/payment_adapter_map';
	const FEEDBACK_RESEND_THRESHOLD = 'radial_fraudinsight/fraud_insight/feedback_request_resend_threshold';

	/**
	 * check if Risk Insight module is enable in the store config
	 *
	 * @param  mixed
	 * @return bool
	 */
	public function isEnabled($store=null)
	{
		return Mage::getStoreConfigFlag(static::ENABLED, $store);
	}

	/**
	 * check if debug mode is enable in the store config
	 *
	 * @param  mixed
	 * @return bool
	 */
	public function isDebugMode($store=null)
	{
		return Mage::getStoreConfigFlag(static::DEBUG, $store);
	}

	/**
	 * retrieve the FraudNet Store id from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getStoreId($store=null)
	{
		return Mage::getStoreConfig(static::STORE_ID, $store);
	}

	/**
	 * retrieve the language code from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getLanguageCodeId($store=null)
	{
		return Mage::getStoreConfig(static::LANGUAGE_CODE, $store);
	}

	/**
	 * retrieve the API Host Name from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getApiHostname($store=null)
	{
		return Mage::getStoreConfig(static::API_HOSTNAME, $store);
	}

	/**
	 * retrieve the API encrypted key from store config and decrypt it.
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getApiKey($store=null)
	{
		return Mage::helper('core')->decrypt(Mage::getStoreConfig(static::API_KEY, $store));
	}

	/**
	 * retrieve the API timeout setting from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getApiTimeout($store=null)
	{
		return Mage::getStoreConfig(static::API_TIMEOUT, $store);
	}

	/**
	 * retrieve the order source override setting from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getOrderSource($store=null)
	{
		return Mage::getStoreConfig(static::ORDER_SOURCE, $store);
	}

	/**
	 * retrieve the high response action settings from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getHighResponseAction($store=null)
	{
		return Mage::getStoreConfig(static::HIGH_RESPONSE_ACTION, $store);
	}

	/**
	 * retrieve the medium response action settings from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getMediumResponseAction($store=null)
	{
		return Mage::getStoreConfig(static::MEDIUM_RESPONSE_ACTION, $store);
	}

	/**
	 * retrieve the low response action settings from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getLowResponseAction($store=null)
	{
		return Mage::getStoreConfig(static::LOW_RESPONSE_ACTION, $store);
	}

	/**
	 * retrieve the unknown response action settings from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getUnknownResponseAction($store=null)
	{
		return Mage::getStoreConfig(static::UNKNOWN_RESPONSE_ACTION, $store);
	}

	/**
	 * retrieve the payment method card type map settings from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getPaymentMethodCardTypeMap($store=null)
	{
		return Mage::getStoreConfig(static::CARD_TYPE_MAP, $store);
	}

	/**
	 * retrieve the shipping method map settings from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getShippingMethodMap($store=null)
	{
		return Mage::getStoreConfig(static::SHIPPING_METHOD_MAP, $store);
	}

	/**
	 * retrieve the payment adapter map settings from store config
	 *
	 * @param  mixed
	 * @return string
	 */
	public function getPaymentAdapterMap($store=null)
	{
		return Mage::getStoreConfig(static::PAYMENT_ADAPTER_MAP, $store);
	}

	/**
	 * retrieve the feedback request resend threshold settings from store config
	 *
	 * @param  mixed
	 * @return int
	 */
	public function getFeedbackResendThreshold($store=null)
	{
		return (int) Mage::getStoreConfig(static::FEEDBACK_RESEND_THRESHOLD, $store);
	}
}
