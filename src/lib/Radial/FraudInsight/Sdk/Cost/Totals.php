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

class Radial_FraudInsight_Sdk_Cost_Totals
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_Cost_ITotals
{
	/** @var string */
	protected $_currencyCode;
	/** @var float */
	protected $_amountBeforeTax;
	/** @var float */
	protected $_amountAfterTax;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->_extractionPaths = array(
			'setCurrencyCode' => 'string(x:CurrencyCode)',
			'setAmountBeforeTax' => 'number(x:AmountBeforeTax)',
		);
		$this->_optionalExtractionPaths = array(
			'setAmountAfterTax' => 'x:AmountAfterTax',
		);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Cost_ITotals::getCurrencyCode()
	 */
	public function getCurrencyCode()
	{
		return $this->_currencyCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Cost_ITotals::setCurrencyCode()
	 */
	public function setCurrencyCode($currencyCode)
	{
		$this->_currencyCode = $currencyCode;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Cost_ITotals::getAmountBeforeTax()
	 */
	public function getAmountBeforeTax()
	{
		return $this->_amountBeforeTax;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Cost_ITotals::setAmountBeforeTax()
	 */
	public function setAmountBeforeTax($amountBeforeTax)
	{
		$this->_amountBeforeTax = $amountBeforeTax;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Cost_ITotals::getAmountAfterTax()
	 */
	public function getAmountAfterTax()
	{
		return $this->_amountAfterTax;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Cost_ITotals::setAmountAfterTax()
	 */
	public function setAmountAfterTax($amountAfterTax)
	{
		$this->_amountAfterTax = $amountAfterTax;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_canSerialize()
	 */
	protected function _canSerialize()
	{
		return (trim($this->getCurrencyCode()) !== '' && trim($this->getAmountBeforeTax()) !== '');
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
		return $this->_serializeNode('CurrencyCode', $this->getCurrencyCode())
			. $this->_serializeAmountNode('AmountBeforeTax', $this->getAmountBeforeTax())
			. $this->_serializeOptionalAmount('AmountAfterTax', $this->getAmountAfterTax());
	}
}
