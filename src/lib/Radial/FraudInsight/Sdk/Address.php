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

class Radial_FraudInsight_Sdk_Address
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_IAddress
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

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getLineA()
	 */
	public function getLineA()
	{
		return $this->_lineA;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setLineA()
	 */
	public function setLineA($lineA)
	{
		$this->_lineA = $lineA;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getLineB()
	 */
	public function getLineB()
	{
		return $this->_lineB;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setLineB()
	 */
	public function setLineB($lineB)
	{
		$this->_lineB = $lineB;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getLineC()
	 */
	public function getLineC()
	{
		return $this->_lineC;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setLineC()
	 */
	public function setLineC($lineC)
	{
		$this->_lineC = $lineC;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getLineD()
	 */
	public function getLineD()
	{
		return $this->_lineD;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setLineD()
	 */
	public function setLineD($lineD)
	{
		$this->_lineD = $lineD;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getCity()
	 */
	public function getCity()
	{
		return $this->_city;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setCity()
	 */
	public function setCity($city)
	{
		$this->_city = $city;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getPostalCode()
	 */
	public function getPostalCode()
	{
		return $this->_postalCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setPostalCode()
	 */
	public function setPostalCode($postalCode)
	{
		$this->_postalCode = $postalCode;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getMainDivisionCode()
	 */
	public function getMainDivisionCode()
	{
		return $this->_mainDivisionCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setMainDivisionCode()
	 */
	public function setMainDivisionCode($mainDivisionCode)
	{
		$this->_mainDivisionCode = $mainDivisionCode;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::getCountryCode()
	 */
	public function getCountryCode()
	{
		return $this->_countryCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IAddress::setCountryCode()
	 */
	public function setCountryCode($countryCode)
	{
		$this->_countryCode = $countryCode;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_canSerialize()
	 */
	protected function _canSerialize()
	{
		return (
			trim($this->getLineA()) !== ''
			&& trim($this->getPostalCode()) !== ''
			&& trim($this->getCountryCode()) !== ''
		);
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
