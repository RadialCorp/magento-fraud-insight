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

class Radial_FraudInsight_Sdk_Transaction_Response
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_Transaction_IResponse
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

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->_extractionPaths = array(
			'setResponse' => 'string(.)',
			'setResponseType' => 'string(@ResponseType)',
		);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Transaction_IResponse::getResponse()
	 */
	public function getResponse()
	{
		return $this->_response;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Transaction_IResponse::setResponse()
	 */
	public function setResponse($response)
	{
		$this->_response = $response;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Transaction_IResponse::getResponseType()
	 */
	public function getResponseType()
	{
		return $this->_responseType;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Transaction_IResponse::setResponseType()
	 */
	public function setResponseType($responseType)
	{
		$this->_responseType = $responseType;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_canSerialize()
	 */
	protected function _canSerialize()
	{
		return in_array($this->getResponse(), $this->_responseEnums)
			&& in_array($this->getResponseType(), $this->_responseTypeEnums);
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
		return $this->getResponse();
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootAttributes()
	 */
	protected function _getRootAttributes()
	{
		return array(
			'ResponseType' => $this->getResponseType(),
		);
	}
}
