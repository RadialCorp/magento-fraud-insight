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

class EbayEnterprise_RiskInsight_Model_Address
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_IAddress
{
	/** @var string */
	protected $_lineA;
	/** @var string */
	protected $_lineB;
	/** @var string */
	protected $_lineC;
	/** @var string */
	protected $_lineD;
	/** @var string */
	protected $_city;
	/** @var string */
	protected $_postalCode;
	/** @var string */
	protected $_mainDivisionCode;
	/** @var string */
	protected $_countryCode;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->_extractionPaths = array(
			'setLineA' => 'string(x:Line1)',
			'setPostalCode' => 'string(x:PostalCode)',
			'setCountryCode' => 'string(x:CountryCode)',
		);
		$this->_optionalExtractionPaths = array(
			'setLineB' => 'x:Line2',
			'setLineC' => 'x:Line3',
			'setLineD' => 'x:Line4',
			'setCity' => 'x:City',
			'setMainDivisionCode' => 'x:MainDivisionCode',
		);
	}

	public function getLineA()
	{
		return $this->_lineA;
	}

	public function setLineA($lineA)
	{
		$this->_lineA = $lineA;
		return $this;
	}

	public function getLineB()
	{
		return $this->_lineB;
	}

	public function setLineB($lineB)
	{
		$this->_lineB = $lineB;
		return $this;
	}

	public function getLineC()
	{
		return $this->_lineC;
	}

	public function setLineC($lineC)
	{
		$this->_lineC = $lineC;
		return $this;
	}

	public function getLineD()
	{
		return $this->_lineD;
	}

	public function setLineD($lineD)
	{
		$this->_lineD = $lineD;
		return $this;
	}

	public function getCity()
	{
		return $this->_city;
	}

	public function setCity($city)
	{
		$this->_city = $city;
		return $this;
	}

	public function getPostalCode()
	{
		return $this->_postalCode;
	}

	public function setPostalCode($postalCode)
	{
		$this->_postalCode = $postalCode;
		return $this;
	}

	public function getMainDivisionCode()
	{
		return $this->_mainDivisionCode;
	}

	public function setMainDivisionCode($mainDivisionCode)
	{
		$this->_mainDivisionCode = $mainDivisionCode;
		return $this;
	}

	public function getCountryCode()
	{
		return $this->_countryCode;
	}

	public function setCountryCode($countryCode)
	{
		$this->_countryCode = $countryCode;
		return $this;
	}

	protected function _canSerialize()
	{
		return (
			trim($this->getLineA()) !== ''
			&& trim($this->getPostalCode()) !== ''
			&& trim($this->getCountryCode()) !== ''
		);
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
		return $this->_serializeNode('Line1', $this->getLineA())
			. $this->_serializeOptionalValue('Line2', $this->getLineB())
			. $this->_serializeOptionalValue('Line3', $this->getLineC())
			. $this->_serializeOptionalValue('Line4', $this->getLineD())
			. $this->_serializeOptionalValue('City', $this->getCity())
			. $this->_serializeNode('PostalCode', $this->getPostalCode())
			. $this->_serializeOptionalValue('MainDivisionCode', $this->getMainDivisionCode())
			. $this->_serializeNode('CountryCode', $this->getCountryCode());
	}
}
