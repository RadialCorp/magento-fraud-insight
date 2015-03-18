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

class EbayEnterprise_RiskInsight_Model_Telephone
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_ITelephone
{
	/** @var string $_countryCode */
	protected $_countryCode;
	/** @var string $_areaCode */
	protected $_areaCode;
	/** @var string $_number */
	protected $_number;
	/** @var string $_extension */
	protected $_extension;

	public function __construct()
	{
		$this->_extractionPaths = array(
			'setNumber' => 'string(x:Number)',
		);
		$this->_optionalExtractionPaths = array(
			'setCountryCode' => 'x:CountryCode',
			'setAreaCode' => 'x:AreaCode',
			'setExtension' => 'x:Extension',
		);
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

	public function getAreaCode()
	{
		return $this->_areaCode;
	}

	public function setAreaCode($areaCode)
	{
		$this->_areaCode = $areaCode;
		return $this;
	}

	public function getNumber()
	{
		return $this->_number;
	}

	public function setNumber($number)
	{
		$this->_number = $number;
		return $this;
	}

	public function getExtension()
	{
		return $this->_extension;
	}

	public function setExtension($extension)
	{
		$this->_extension = $extension;
		return $this;
	}

	protected function _canSerialize()
	{
		return (trim($this->getNumber()) !== '');
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
		return $this->_serializeOptionalValue('CountryCode', $this->getCountryCode())
			. $this->_serializeOptionalValue('AreaCode', $this->getAreaCode())
			. $this->_serializeNode('Number', $this->getNumber())
			. $this->_serializeOptionalValue('Extension', $this->getExtension());
	}
}
