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

class EbayEnterprise_RiskInsight_Model_Transaction_Response
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_Transaction_IResponse
{
	/** @var string */
	protected $_response;
	/** @var string */
	protected $_responseType;
	/** @var array */
	protected $_responseTypeEnums = array(
		'avsAddr',
		'avsZip',
		'3ds',
		'cvv2',
		'PayPalPayer',
		'PayPalAddress',
		'PayPalPayerCountry',
		'PayPalSellerProtection',
		'AmexName',
		'AmexEmail',
		'AmexPhone'
	);
	/** @var array */
	protected $_responseEnums = array('M', 'N', 'confirmed', 'verified', 'X');

	public function __construct()
	{
		$this->_extractionPaths = array(
			'setResponse' => 'string(.)',
			'setResponseType' => 'string(@ResponseType)',
		);
	}

	public function getResponse()
	{
		return $this->_response;
	}

	public function setResponse($response)
	{
		$this->_response = $response;
		return $this;
	}

	public function getResponseType()
	{
		return $this->_responseType;
	}

	public function setResponseType($responseType)
	{
		$this->_responseType = $responseType;
		return $this;
	}

	protected function _canSerialize()
	{
		return in_array($this->getResponse(), $this->_responseEnums)
			&& in_array($this->getResponseType(), $this->_responseTypeEnums);
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
		return $this->getResponse();
	}

	protected function _getRootAttributes()
	{
		return array(
			'ResponseType' => $this->getResponseType(),
		);
	}
}
