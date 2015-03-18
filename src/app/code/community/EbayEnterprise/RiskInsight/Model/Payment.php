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

class EbayEnterprise_RiskInsight_Model_Payment
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_IPayment
{
	/** @var EbayEnterprise_RiskInsight_Model_Payment_ICard $_paymentCard */
	protected $_paymentCard;
	/** @var EbayEnterprise_RiskInsight_Model_Person_IName $_personName */
	protected $_personName;
	/** @var string $_email */
	protected $_email;
	/** @var EbayEnterprise_RiskInsight_Model_ITelephone $_telephone */
	protected $_telephone;
	/** @var EbayEnterprise_RiskInsight_Model_IAddress $_address */
	protected $_address;
	/** @var DateTime $_paymentTransactionDate */
	protected $_paymentTransactionDate;
	/** @var string $_currencyCode */
	protected $_currencyCode;
	/** @var float $_amount */
	protected $_amount;
	/** @var int $_totalAuthAttemptCount */
	protected $_totalAuthAttemptCount;
	/** @var EbayEnterprise_RiskInsight_Model_Transaction_IResponses $_transactionResponses */
	protected $_transactionResponses;

	public function __construct()
	{
		$this->setPaymentCard($this->_buildPayloadForModel(static::PAYMENT_CARD_MODEL));
		$this->setPersonName($this->_buildPayloadForModel(static::PERSON_NAME_MODEL));
		$this->setTelephone($this->_buildPayloadForModel(static::TELEPHONE_MODEL));
		$this->setAddress($this->_buildPayloadForModel(static::ADDRESS_MODEL));
		$this->setTransactionResponses($this->_buildPayloadForModel(static::TRANSACTION_RESPONSES_MODEL));
		$this->_extractionPaths = array(
			'setEmail' => 'string(x:Email)',
		);
		$this->_optionalExtractionPaths = array(
			'setCurrencyCode' => 'x:CurrencyCode',
			'setAmount' => 'x:Amount',
			'setTotalAuthAttemptCount' => 'x:TotalAuthAttemptCount',
		);
		$this->_dateTimeExtractionPaths = array(
			'setPaymentTransactionDate' => 'string(x:PaymentTransactionDate)',
		);
		$this->_subpayloadExtractionPaths = array(
			'setPaymentCard' => 'x:PaymentCard',
			'setPersonName' => 'x:PersonName',
			'setTelephone' => 'x:Telephone',
			'setAddress' => 'x:Address',
			'setTransactionResponses' => 'x:TransactionResponses',
		);
	}

	public function getPaymentCard()
	{
		return $this->_paymentCard;
	}

	public function setPaymentCard(EbayEnterprise_RiskInsight_Model_Payment_ICard $paymentCard)
	{
		$this->_paymentCard = $paymentCard;
		return $this;
	}

	public function getPersonName()
	{
		return $this->_personName;
	}

	public function setPersonName(EbayEnterprise_RiskInsight_Model_Person_IName $personName)
	{
		$this->_personName = $personName;
		return $this;
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function setEmail($email)
	{
		$this->_email = $email;
		return $this;
	}

	public function getTelephone()
	{
		return $this->_telephone;
	}

	public function setTelephone(EbayEnterprise_RiskInsight_Model_ITelephone $telephone)
	{
		$this->_telephone = $telephone;
		return $this;
	}

	public function getAddress()
	{
		return $this->_address;
	}

	public function setAddress(EbayEnterprise_RiskInsight_Model_IAddress $address)
	{
		$this->_address = $address;
		return $this;
	}

	public function getPaymentTransactionDate()
	{
		return $this->_paymentTransactionDate;
	}

	public function setPaymentTransactionDate(DateTime $paymentTransactionDate)
	{
		$this->_paymentTransactionDate = $paymentTransactionDate;
		return $this;
	}

	public function getCurrencyCode()
	{
		return $this->_currencyCode;
	}

	public function setCurrencyCode($currencyCode)
	{
		$this->_currencyCode = $currencyCode;
		return $this;
	}

	public function getAmount()
	{
		return $this->_amount;
	}

	public function setAmount($amount)
	{
		$this->_amount = $amount;
		return $this;
	}

	public function getTotalAuthAttemptCount()
	{
		return $this->_totalAuthAttemptCount;
	}

	public function setTotalAuthAttemptCount($totalAuthAttemptCount)
	{
		$this->_totalAuthAttemptCount = $totalAuthAttemptCount;
		return $this;
	}

	public function getTransactionResponses()
	{
		return $this->_transactionResponses;
	}

	public function setTransactionResponses(EbayEnterprise_RiskInsight_Model_Transaction_IResponses $transactionResponses)
	{
		$this->_transactionResponses = $transactionResponses;
		return $this;
	}

	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	protected function _getXmlNamespace()
	{
		return self::XML_NS;
	}

	protected function _serializeContents()
	{
		return $this->getPaymentCard()->serialize()
			. $this->getPersonName()->serialize()
			. $this->_serializeNode('Email', $this->getEmail())
			. $this->getTelephone()->serialize()
			. $this->getAddress()->serialize()
			. $this->_serializeOptionalDateValue('PaymentTransactionDate', 'c', $this->getPaymentTransactionDate())
			. $this->_serializeOptionalValue('CurrencyCode', $this->getCurrencyCode())
			. $this->_serializeOptionalAmount('Amount', $this->getAmount())
			. $this->_serializeOptionalNumber('TotalAuthAttemptCount', $this->getTotalAuthAttemptCount())
			. $this->getTransactionResponses()->serialize();
	}
}
