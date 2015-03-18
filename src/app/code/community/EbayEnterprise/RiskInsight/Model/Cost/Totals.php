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

class EbayEnterprise_RiskInsight_Model_Cost_Totals
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_Cost_ITotals
{
	/** @var string $_currencyCode */
	protected $_currencyCode;
	/** @var float $_amountBeforeTax */
	protected $_amountBeforeTax;
	/** @var float $_amountAfterTax */
	protected $_amountAfterTax;

	public function __construct()
	{
		$this->_extractionPaths = array(
			'setCurrencyCode' => 'string(x:CurrencyCode)',
			'setAmountBeforeTax' => 'number(x:AmountBeforeTax)',
		);
		$this->_optionalExtractionPaths = array(
			'setAmountAfterTax' => 'x:AmountAfterTax',
		);
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

	public function getAmountBeforeTax()
	{
		return $this->_amountBeforeTax;
	}

	public function setAmountBeforeTax($amountBeforeTax)
	{
		$this->_amountBeforeTax = $amountBeforeTax;
		return $this;
	}

	public function getAmountAfterTax()
	{
		return $this->_amountAfterTax;
	}

	public function setAmountAfterTax($amountAfterTax)
	{
		$this->_amountAfterTax = $amountAfterTax;
		return $this;
	}

	protected function _canSerialize()
	{
		return (trim($this->getCurrencyCode()) !== '' && trim($this->getAmountBeforeTax()) !== '');
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
		return $this->_serializeNode('CurrencyCode', $this->getCurrencyCode())
			. $this->_serializeAmountNode('AmountBeforeTax', $this->getAmountBeforeTax())
			. $this->_serializeOptionalAmount('AmountAfterTax', $this->getAmountAfterTax());
	}
}
