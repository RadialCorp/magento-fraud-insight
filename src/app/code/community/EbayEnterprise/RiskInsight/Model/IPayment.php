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
	 * @param  EbayEnterprise_RiskInsight_Model_Payment_ICard
	 * @return self
	 */
	public function setPaymentCard(EbayEnterprise_RiskInsight_Model_Payment_ICard $paymentCard);

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
	 * @return EbayEnterprise_RiskInsight_Model_Transaction_IResponses
	 */
	public function getTransactionResponses();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Transaction_IResponses
	 * @return self
	 */
	public function setTransactionResponses(EbayEnterprise_RiskInsight_Model_Transaction_IResponses $transactionResponses);
}
