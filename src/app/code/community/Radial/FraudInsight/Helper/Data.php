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

class Radial_FraudInsight_Helper_Data extends Mage_Core_Helper_Abstract
{
	const DEFAULT_LANGUAGE_CODE = 'en';
	const FREE_PAYMENT_METHOD = 'free';
	const RISK_INSIGHT_GIFT_CARD_PAYMENT_METHOD = 'GC';
	const RISK_INSIGHT_DEFAULT_PAYMENT_METHOD = 'OTHER';

	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;
	/** @var array */
	protected $_paymentMethodMap;

	public function __construct()
	{
		$this->_config = Mage::helper('radial_fraudinsight/config');
		$this->_paymentMethodMap = $this->_config->getPaymentMethodCardTypeMap();
	}

	/**
	 * Get all header data.
	 *
	 * @return array
	 */
	public function getHeaderData()
	{
		$headers = array();
        $server = $this->_getRequest()->getServer();
		foreach ($server as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}

		return $headers;
	}

	/**
	 * Get a collection of risk insight object where UCP request has not been sent.
	 *
	 * @return Radial_FraudInsight_Model_Resource_Risk_Insight_Collection
	 */
	public function getRiskInsightCollection()
	{
		return Mage::getResourceModel('radial_fraudinsight/risk_insight_collection')
			->addFieldToFilter('is_request_sent', 0);
	}

	/**
	 * Get a collection of risk insight object where the risk insight request had already been sent,
	 * there was no successful feedback request sent, and the fail attempt feedback request counter is
	 * less than the configured threshold.
	 *
	 * @return Radial_FraudInsight_Model_Resource_Risk_Insight_Collection
	 */
	public function getFeedbackOrderCollection()
	{
		return Mage::getResourceModel('radial_fraudinsight/risk_insight_collection')
			->addFieldToFilter('is_request_sent', 1)
			->addFieldToFilter('is_feedback_sent', 0)
			->addFieldToFilter('feedback_sent_attempt_count', array(
				'lt' => $this->_config->getFeedbackResendThreshold()
			));
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
	 * Get a loaded risk insight object by order increment id from the passed in sales order object.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	public function getRiskInsight(Mage_Sales_Model_Order $order)
	{
		return Mage::getModel('radial_fraudinsight/risk_insight')
			->load($order->getIncrementId(), 'order_increment_id');
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
	 * Get the source of an order, determined by the area in which the order
	 * was placed: admin or frontend.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return string
	 */
	public function getOrderSourceByArea(Mage_Sales_Model_Order $order)
	{
		return $this->_isAdminOrder($order)
			? Radial_FraudInsight_Model_System_Config_Source_Ordersource::DASHBOARD
			: Radial_FraudInsight_Model_System_Config_Source_Ordersource::WEBSTORE;
	}

	/**
	 * Determine if the passed in order object was created from the admin interface.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return bool
	 */
	protected function _isAdminOrder(Mage_Sales_Model_Order $order)
	{
		// Magento store remote ip address for front-end customer order and not for Admin orders.
		// For more information reference this link http://magento.stackexchange.com/questions/16757/
		return !$order->getRemoteIp();
	}

	/**
	 * @param  Mage_Sales_Model_Order
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return bool
	 */
	public function isGiftCardPayment(
		Mage_Sales_Model_Order $order,
		Mage_Sales_Model_Order_Payment $payment
	)
	{
		return ($this->_hasGiftCard($order) && ($payment->getMethod() === static::FREE_PAYMENT_METHOD));
	}

	/**
	 * Determine if the passed in order has gift card data.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return bool
	 */
	protected function _hasGiftCard(Mage_Sales_Model_Order $order)
	{
		$giftCards = $this->getGiftCard($order);
		return !empty($giftCards);
	}

	/**
	 * Get the gift card data in the order.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return array
	 */
	public function getGiftCard(Mage_Sales_Model_Order $order)
	{
		return (array) unserialize($order->getGiftCards());
	}

	/**
	 * Used configuration map to retrieve enumerated value for the risk insight request.
	 *
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return string
	 */
	public function getMapRiskInsightPaymentMethod(Mage_Sales_Model_Order_Payment $payment)
	{
		$method = $this->getPaymentMethodValueFromMap($payment->getCcType())
			?: $this->getPaymentMethodValueFromMap($payment->getMethod());
		return $method ?: static::RISK_INSIGHT_DEFAULT_PAYMENT_METHOD;
	}

	/**
	 * @param  string
	 * @return string | null
	 */
	public function getPaymentMethodValueFromMap($key)
	{
		return isset($this->_paymentMethodMap[$key]) ? $this->_paymentMethodMap[$key] : null;
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
		return $this->getFirstSixChars($cc);
	}

	/**
	 * Get the first 6 characters from a passed in string
	 *
	 * @param  string
	 * @return string
	 */
	public function getFirstSixChars($string)
	{
		return $string ? substr($string, 0, 6) : $string;
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
		return $cc ? $this->hashAndEncodeCc($cc) : null;
	}

	/**
	 * Return a hash and base64 encoded string of the passed in credit card number.
	 * @param  string $cc
	 * @return string
	 */
	public function hashAndEncodeCc($cc)
	{
		return base64_encode(hash('sha1', $cc, true));
	}

	/**
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return string | null
	 */
	public function getPaymentExpireDate(Mage_Sales_Model_Order_Payment $payment)
	{
		$month = $payment->getCcExpMonth();
		$year = $payment->getCcExpYear();
		return ($year > 0 && $month > 0) ? $this->getYearMonth($year, $month) : null;
	}

	/**
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public function getYearMonth($year, $month)
	{
		return $year . '-' . $this->_correctMonth($month);
	}

	/**
	 * @param  string
	 * @return string
	 */
	protected function _correctMonth($month)
	{
		return (strlen($month) === 1) ? sprintf('%02d', $month) : $month;
	}

	/**
	 * Determine if the passed order can be used to send feedback request.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @return bool
	 */
	public function isOrderInAStateToSendFeedback(Mage_Sales_Model_Order $order)
	{
		return (
			$order->getState() === Mage_Sales_Model_Order::STATE_CANCELED
			|| $order->getState() === Mage_Sales_Model_Order::STATE_COMPLETE
		);
	}

	/**
	 * Determine if feedback request can be sent.
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @return bool
	 */
	public function canHandleFeedback(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Model_Risk_Insight $insight
	)
	{
		return (
			$this->isOrderInAStateToSendFeedback($order)
			&& (bool) $insight->getIsRequestSent() === true
			&& (bool) $insight->getIsFeedbackSent() === false
			&& (int) $insight->getFeedbackSentAttemptCount() < $this->_config->getFeedbackResendThreshold()
		);
	}
}
