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

class EbayEnterprise_RiskInsight_Model_Payment_Card
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_Payment_ICard
{
	/** @var string */
	protected $_cardHolderName;
	/** @var string */
	protected $_paymentAccountUniqueId;
	/** @var bool */
	protected $_isToken;
	/** @var string */
	protected $_paymentAccountBin;
	/** @var string */
	protected $_expireDate;
	/** @var string */
	protected $_cardType;

	public function __construct()
	{
		$this->_optionalExtractionPaths = array(
			'setCardHolderName' => 'x:CardHolderName',
			'setPaymentAccountUniqueId' => 'x:PaymentAccountUniqueId',
			'setPaymentAccountBin' => 'x:PaymentAccountBin',
			'setExpireDate' => 'x:ExpireDate',
			'setCardType' => 'x:CardType',
		);
		$this->_booleanExtractionPaths = array(
			'setIsToken' => 'string(x:PaymentAccountUniqueId/@isToken)',
		);
	}

	public function getCardHolderName()
	{
		return $this->_cardHolderName;
	}

	public function setCardHolderName($cardHolderName)
	{
		$this->_cardHolderName = $cardHolderName;
		return $this;
	}

	public function getPaymentAccountUniqueId()
	{
		return $this->_paymentAccountUniqueId;
	}

	public function setPaymentAccountUniqueId($paymentAccountUniqueId)
	{
		$this->_paymentAccountUniqueId = $paymentAccountUniqueId;
		return $this;
	}

	public function getIsToken()
	{
		return $this->_isToken;
	}

	public function setIsToken($isToken)
	{
		$this->_isToken = $isToken;
		return $this;
	}

	public function getPaymentAccountBin()
	{
		return $this->_paymentAccountBin;
	}

	public function setPaymentAccountBin($paymentAccountBin)
	{
		$this->_paymentAccountBin = $paymentAccountBin;
		return $this;
	}

	public function getExpireDate()
	{
		return $this->_expireDate;
	}

	public function setExpireDate($expireDate)
	{
		$this->_expireDate = $expireDate;
		return $this;
	}

	public function getCardType()
	{
		return $this->_cardType;
	}

	public function setCardType($cardType)
	{
		$this->_cardType = $cardType;
		return $this;
	}

	protected function _canSerialize()
	{
		return (trim($this->_serializeContents()) !== '');
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
		return $this->_serializeOptionalValue('CardHolderName', $this->getCardHolderName())
			. $this->_serializePaymentAccountUniqueId()
			. $this->_serializeOptionalValue('PaymentAccountBin', $this->getPaymentAccountBin())
			. $this->_serializeOptionalValue('ExpireDate', $this->getExpireDate())
			. $this->_serializeOptionalValue('CardType', $this->getCardType());
	}

	protected function _serializePaymentAccountUniqueId()
	{
		$isToken = $this->getIsToken();
		$isToken = !is_null($isToken) ? $isToken : false;
		$paymentAccountUniqueId = $this->getPaymentAccountUniqueId();
		return $paymentAccountUniqueId ? "<PaymentAccountUniqueId isToken=\"{$isToken}\">{$paymentAccountUniqueId}</PaymentAccountUniqueId>" : '';
	}
}
