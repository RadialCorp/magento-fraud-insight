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

class EbayEnterprise_RiskInsight_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DEFAULT_LANGUAGE_CODE = 'en';
	const FREE_PAYMENT_METHOD = 'free';
	const RISK_INSIGHT_GIFT_CARD_PAYMENT_METHOD = 'GC';
	const RISK_INSIGHT_DEFAULT_PAYMENT_METHOD = 'OTHER';

	/** @var EbayEnterprise_RiskInsight_Helper_Config */
	protected $_config;

	public function __construct()
	{
		$this->_config = Mage::helper('ebayenterprise_riskinsight/config');
	}

	/**
	 * Convert "true", "false", "1" or "0" to boolean
	 * Everything else returns null
	 *
	 * @param  string
	 * @return bool | null
	 */
	public function convertStringToBoolean($string)
	{
		if (!is_string($string)) {
			return null;
		}
		$string = strtolower($string);
		switch ($string) {
			case 'true':
			case '1':
				return true;
			case 'false':
			case '0':
				return false;
		}
		return null;
	}

	/**
	 * Consistent formatting of amounts.
	 *
	 * @param  float
	 * @return string
	 */
	public function formatAmount($amount)
	{
		return sprintf('%01.2F', $amount);
	}

	/**
	 * Load the payload XML into a DOMXPath for querying.
	 *
	 * @param  string
	 * @param  string
	 * @return DOMXPath
	 */
	public function getPayloadAsXPath($xmlString, $nameSpace)
	{
		$xpath = new DOMXPath($this->getPayloadAsDoc($xmlString));
		$xpath->registerNamespace('x', $nameSpace);
		return $xpath;
	}

	/**
	 * Load the payload XML into a DOMDocument
	 *
	 * @param  string
	 * @return DOMDocument
	 */
	public function getPayloadAsDoc($xmlString)
	{
		$d = new DOMDocument();
		try {
			$d->loadXML($xmlString);
		} catch (Exception $e) {
			throw $e;
		}
		return $d;
	}

	/**
	 * Get all header data.
	 *
	 * @return array
	 */
	public function getHeaderData()
	{
		$headers = array();
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}

	/**
	 * Get a collection of risk insight object where UCP request has not been sent.
	 *
	 * @return array
	 */
	public function getRiskInsightCollection()
	{
		return Mage::getResourceModel('ebayenterprise_riskinsight/risk_insight_collection')
			->addFieldToFilter('is_request_sent', 0);
	}

	/**
	 * Getting a collection of sales/order object filtered by increment ids.
	 *
	 * @param  array
	 * @return Mage_Sales_Model_Resource_Order_Collection
	 */
	public function getOrderCollectionByIncrementIds(array $incrementIds=array())
	{
		return Mage::getResourceModel('sales/order_collection')
			->addFieldToFilter('increment_id', array('in' => $incrementIds));
	}

	/**
	 * @param  string
	 * @return DateTime
	 */
	public function getNewDateTime($dateTime)
	{
		return new DateTime($dateTime);
	}

	/**
	 * Get Magento locale language code.
	 *
	 * @return string
	 */
	public function getLanguageCode()
	{
		$locale = trim(Mage::app()->getLocale()->getLocaleCode());
		return $locale ? substr($locale, 0, 2) : static::DEFAULT_LANGUAGE_CODE;
	}

	/**
	 * @param  Mage_Sales_Model_Order
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	public function getRiskInsight(Mage_Sales_Model_Order $order)
	{
		$insight = Mage::getModel('ebayenterprise_riskinsight/risk_insight');
		$insight->load($order->getIncrementId(), 'order_increment_id');
		return $insight;
	}

	/**
	 * Check if risk insight request has already been sent for the passed in order.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return bool
	 */
	public function isRiskInsightRequestSent(Mage_Sales_Model_Order $order)
	{
		return ((int) $this->getRiskInsight($order)->getIsRequestSent() === 1);
	}

	/**
	 * @return string
	 */
	public function getOrderSourceByArea()
	{
		return Mage::app()->getStore()->isAdmin()
			? EbayEnterprise_RiskInsight_Model_System_Config_Source_Ordersource::DASHBOARD
			: EbayEnterprise_RiskInsight_Model_System_Config_Source_Ordersource::WEBSTORE;
	}

	/**
	 * @param  Mage_Sales_Model_Order
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return string
	 */
	public function getPaymentMethod(
		Mage_Sales_Model_Order $order,
		Mage_Sales_Model_Order_Payment $payment
	)
	{
		return ($this->_hasGiftCard($order) && ($payment->getMethod() === static::FREE_PAYMENT_METHOD))
			? static::RISK_INSIGHT_GIFT_CARD_PAYMENT_METHOD
			: $this->_getMapRiskInsightPaymentMethod($payment);
	}

	/**
	 * Determine if the passed in order has gift card data.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return bool
	 */
	protected function _hasGiftCard(Mage_Sales_Model_Order $order)
	{
		$giftCards = unserialize($order->getGiftGards());
		return !empty($giftCards);
	}

	/**
	 * Used configuration map to retrieve enumerated value for the risk insight request.
	 *
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return string
	 */
	protected function _getMapRiskInsightPaymentMethod(Mage_Sales_Model_Order_Payment $payment)
	{
		$map = $this->_config->getPaymentMethodCardTypeMap();
		$method = $this->_getValueFromMap($map, $payment->getCcType())
			?: $this->_getValueFromMap($map, $payment->getMethod());
		return $method ?: static::RISK_INSIGHT_DEFAULT_PAYMENT_METHOD;
	}

	/**
	 * @param  array
	 * @param  string
	 * @return string | null
	 */
	protected function _getValueFromMap(array $map, $key)
	{
		return isset($map[$key]) ? $map[$key] : null;
	}

	/**
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return string | null
	 */
	protected function _decryptCc(Mage_Sales_Model_Order_Payment $payment)
	{
		$encryptedCc = $payment->getCcNumberEnc();
		return $encryptedCc ? Mage::helper('core')->decrypt($encryptedCc) : null;
	}

	/**
	 * Decrypt the encrypted credit card number and return the first 6 digits.
	 *
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return string | null
	 */
	public function getAccountBin(Mage_Sales_Model_Order_Payment $payment)
	{
		$cc = $this->_decryptCc($payment);
		return $cc ? substr($cc, 0, 6) : null;
	}

	/**
	 * Decrypt the encrypted credit card number and return base64 encoded string hash with sha1 algorithm.
	 *
	 * @param  Mage_Sales_Model_Order_Payment $payment
	 * @return string | null
	 */
	public function getAccountUniqueId(Mage_Sales_Model_Order_Payment $payment)
	{
		$cc = $this->_decryptCc($payment);
		return $cc ? $this->_hashAndEncodeCc($cc) : null;
	}

	/**
	 * Return a hash and base64 encoded string of the passed in credit card number.
	 * @param  string $cc
	 * @return string
	 */
	protected function _hashAndEncodeCc($cc)
	{
		return base64_encode(hash('sha1', $cc, true));
	}
}
