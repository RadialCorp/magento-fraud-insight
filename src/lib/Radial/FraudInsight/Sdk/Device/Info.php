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

class Radial_FraudInsight_Sdk_Device_Info
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_Device_IInfo
{
	/** @var string */
	protected $_deviceIP;
	/** @var Radial_FraudInsight_Sdk_Http_Headers */
	protected $_httpHeaders;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->setHttpHeaders($this->_buildPayloadForModel(static::HTTP_HEADERS_MODEL));
		$this->_optionalExtractionPaths = array(
			'setDeviceIP' => 'x:DeviceIP',
		);
		$this->_subpayloadExtractionPaths = array(
			'setHttpHeaders' => 'x:HttpHeaders',
		);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Device_IInfo::getDeviceIP()
	 */
	public function getDeviceIP()
	{
		return $this->_deviceIP;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Device_IInfo::setDeviceIP()
	 */
	public function setDeviceIP($deviceIP)
	{
		$this->_deviceIP = $deviceIP;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Device_IInfo::getHttpHeaders()
	 */
	public function getHttpHeaders()
	{
		return $this->_httpHeaders;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Device_IInfo::setHttpHeaders()
	 */
	public function setHttpHeaders(Radial_FraudInsight_Sdk_Http_Headers $httpHeaders)
	{
		$this->_httpHeaders = $httpHeaders;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::setHttpHeaders()
	 */
	protected function _canSerialize()
	{
		return (trim($this->getDeviceIP()) !== ''|| trim($this->getHttpHeaders()->serialize()) !== '');
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
		return $this->_serializeOptionalValue('DeviceIP', $this->getDeviceIP())
			. $this->getHttpHeaders()->serialize();
	}
}
