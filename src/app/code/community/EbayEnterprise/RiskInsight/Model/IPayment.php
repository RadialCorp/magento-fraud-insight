<?php
/**
 * Copyright (c) 2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

interface EbayEnterprise_RiskInsight_Model_IPayment extends EbayEnterprise_RiskInsight_Model_IPayload
{
	const ROOT_NODE = 'FormOfPayment';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const PAYMENT_CARD_MODEL ='ebayenterprise_riskinsight/payment_card';
	const PERSON_NAME_MODEL ='ebayenterprise_riskinsight/person_name';
	const TELEPHONE_MODEL ='ebayenterprise_riskinsight/telephone';
	const ADDRESS_MODEL ='ebayenterprise_riskinsight/address';
	const TRANSACTION_RESPONSES_MODEL ='ebayenterprise_riskinsight/transaction_responses';

	/**
	 * Contains credit card and card holder information.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_Payment_ICard
	 */
	public function getPaymentCard();
	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Payment_ICard $paymentCard
	 * @return self
	 */
	public function setPaymentCard(EbayEnterprise_RiskInsight_Model_Payment_ICard $paymentCard);
	/**
	 * @return EbayEnterprise_RiskInsight_Model_Person_IName
	 */
	public function getPersonName();
	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Person_IName $personName
	 * @return self
	 */
	public function setPersonName(EbayEnterprise_RiskInsight_Model_Person_IName $personName);
	/**
	 * @return string
	 */
	public function getEmail();
	/**
	 * @param  string $email
	 * @return self
	 */
	public function setEmail($email);
	/**
	 * @return EbayEnterprise_RiskInsight_Model_ITelephone
	 */
	public function getTelephone();
	/**
	 * @param  EbayEnterprise_RiskInsight_Model_ITelephone $telephone
	 * @return self
	 */
	public function setTelephone(EbayEnterprise_RiskInsight_Model_ITelephone $telephone);
	/**
	 * @return EbayEnterprise_RiskInsight_Model_IAddress
	 */
	public function getAddress();
	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IAddress $address
	 * @return self
	 */
	public function setAddress(EbayEnterprise_RiskInsight_Model_IAddress $address);
	/**
	 * @return DateTime
	 */
	public function getPaymentTransactionDate();
	/**
	 * @param  DateTime $paymentTransactionDate
	 * @return self
	 */
	public function setPaymentTransactionDate(DateTime $paymentTransactionDate);
	/**
	 * @return string
	 */
	public function getCurrencyCode();
	/**
	 * @param  string $currencyCode
	 * @return self
	 */
	public function setCurrencyCode($currencyCode);
	/**
	 * @return float
	 */
	public function getAmount();
	/**
	 * @param  float $amount
	 * @return self
	 */
	public function setAmount($amount);
	/**
	 * @return int
	 */
	public function getTotalAuthAttemptCount();
	/**
	 * @param  int $totalAuthAttemptCount
	 * @return self
	 */
	public function setTotalAuthAttemptCount($totalAuthAttemptCount);
	/**
	 * Contains the list of responses from the payment processor.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_Transaction_IResponses
	 */
	public function getTransactionResponses();
	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Transaction_IResponses $transactionResponses
	 * @return self
	 */
	public function setTransactionResponses(EbayEnterprise_RiskInsight_Model_Transaction_IResponses $transactionResponses);
}
