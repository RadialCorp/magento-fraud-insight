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

class Radial_FraudInsight_Sdk_Payment
	extends Radial_FraudInsight_Sdk_Info
	implements Radial_FraudInsight_Sdk_IPayment
{
	/** @var Radial_FraudInsight_Sdk_Payment_ICard */
	protected $_paymentCard;
	/** @var DateTime */
	protected $_paymentTransactionDate;
	/** @var string */
	protected $_currencyCode;
	/** @var float */
	protected $_amount;
	/** @var int */
	protected $_totalAuthAttemptCount;
	/** @var Radial_FraudInsight_Sdk_Transaction_IResponses */
	protected $_transactionResponses;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
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

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::getPaymentCard()
	 */
	public function getPaymentCard()
	{
		return $this->_paymentCard;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::setPaymentCard()
	 */
	public function setPaymentCard(Radial_FraudInsight_Sdk_Payment_ICard $paymentCard)
	{
		$this->_paymentCard = $paymentCard;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::getPaymentTransactionDate()
	 */
	public function getPaymentTransactionDate()
	{
		return $this->_paymentTransactionDate;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::setPaymentTransactionDate()
	 */
	public function setPaymentTransactionDate(DateTime $paymentTransactionDate)
	{
		$this->_paymentTransactionDate = $paymentTransactionDate;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::getCurrencyCode()
	 */
	public function getCurrencyCode()
	{
		return $this->_currencyCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::setCurrencyCode()
	 */
	public function setCurrencyCode($currencyCode)
	{
		$this->_currencyCode = $currencyCode;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::getAmount()
	 */
	public function getAmount()
	{
		return $this->_amount;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::setAmount()
	 */
	public function setAmount($amount)
	{
		$this->_amount = $amount;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::getTotalAuthAttemptCount()
	 */
	public function getTotalAuthAttemptCount()
	{
		return $this->_totalAuthAttemptCount;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::setTotalAuthAttemptCount()
	 */
	public function setTotalAuthAttemptCount($totalAuthAttemptCount)
	{
		$this->_totalAuthAttemptCount = $totalAuthAttemptCount;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::getTransactionResponses()
	 */
	public function getTransactionResponses()
	{
		return $this->_transactionResponses;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IPayment::setTransactionResponses()
	 */
	public function setTransactionResponses(Radial_FraudInsight_Sdk_Transaction_IResponses $transactionResponses)
	{
		$this->_transactionResponses = $transactionResponses;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootNodeName()
	 */
	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getXmlNamespace()
	 */
	protected function _getXmlNamespace()
	{
		return self::XML_NS;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_serializeContents()
	 */
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
