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

class EbayEnterprise_RiskInsight_Model_Device_Info
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_Device_IInfo
{
	/** @var string $_deviceIP */
	protected $_deviceIP;
	/** @var EbayEnterprise_RiskInsight_Model_Http_Headers $_httpHeaders */
	protected $_httpHeaders;

	public function __construct()
	{
		$this->setHttpHeaders($this->_buildPayloadForModel(static::HTTP_HEADERS_MODEL));
		$this->_optionalExtractionPaths = array(
			'setDeviceIP' => 'x:DeviceIP',
		);
		$this->_subpayloadExtractionPaths = array(
			'setHttpHeaders' => 'x:HttpHeaders',
		);
	}

	public function getDeviceIP()
	{
		return $this->_deviceIP;
	}

	public function setDeviceIP($deviceIP)
	{
		$this->_deviceIP = $deviceIP;
		return $this;
	}

	public function getHttpHeaders()
	{
		return $this->_httpHeaders;
	}

	public function setHttpHeaders(EbayEnterprise_RiskInsight_Model_Http_Headers $httpHeaders)
	{
		$this->_httpHeaders = $httpHeaders;
		return $this;
	}

	protected function _canSerialize()
	{
		return (trim($this->getDeviceIP()) !== ''|| trim($this->getHttpHeaders()->serialize()) !== '');
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
		return $this->_serializeOptionalValue('DeviceIP', $this->getDeviceIP())
			. $this->getHttpHeaders()->serialize();
	}
}
