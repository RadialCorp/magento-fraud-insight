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

interface Radial_FraudInsight_Sdk_IPayment extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'FormOfPayment';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const PAYMENT_CARD_MODEL ='Radial_FraudInsight_Sdk_Payment_Card';
	const PERSON_NAME_MODEL ='Radial_FraudInsight_Sdk_Person_Name';
	const TELEPHONE_MODEL ='Radial_FraudInsight_Sdk_Telephone';
	const ADDRESS_MODEL ='Radial_FraudInsight_Sdk_Address';
	const TRANSACTION_RESPONSES_MODEL ='Radial_FraudInsight_Sdk_Transaction_Responses';

	/**
	 * Contains credit card and card holder information.
	 *
	 * @return Radial_FraudInsight_Sdk_Payment_ICard
	 */
	public function getPaymentCard();

	/**
	 * @param  Radial_FraudInsight_Sdk_Payment_ICard
	 * @return self
	 */
	public function setPaymentCard(Radial_FraudInsight_Sdk_Payment_ICard $paymentCard);

	/**
	 * @return DateTime
	 */
	public function getPaymentTransactionDate();

	/**
	 * @param  DateTime
	 * @return self
	 */
	public function setPaymentTransactionDate(DateTime $paymentTransactionDate);

	/**
	 * @return string
	 */
	public function getCurrencyCode();

	/**
	 * @param  string
	 * @return self
	 */
	public function setCurrencyCode($currencyCode);

	/**
	 * @return float
	 */
	public function getAmount();

	/**
	 * @param  float
	 * @return self
	 */
	public function setAmount($amount);

	/**
	 * @return int
	 */
	public function getTotalAuthAttemptCount();

	/**
	 * @param  int
	 * @return self
	 */
	public function setTotalAuthAttemptCount($totalAuthAttemptCount);

	/**
	 * Contains the list of responses from the payment processor.
	 *
	 * @return Radial_FraudInsight_Sdk_Transaction_IResponses
	 */
	public function getTransactionResponses();

	/**
	 * @param  Radial_FraudInsight_Sdk_Transaction_IResponses
	 * @return self
	 */
	public function setTransactionResponses(Radial_FraudInsight_Sdk_Transaction_IResponses $transactionResponses);
}
