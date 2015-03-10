<?php
/**
 * Copyright (c) 2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

class EbayEnterprise_RiskInsight_Model_Error
	extends EbayEnterprise_RiskInsight_Model_Payload_Top
	implements EbayEnterprise_RiskInsight_Model_IError
{
	/** @var string */
	protected $_primaryLangId;
	/** @var string */
	protected $_orderId;
	/** @var string */
	protected $_storeId;
	/** @var string */
	protected $_errorCode;
	/** @var string */
	protected $_errorDescription;
	/** @var string */
	protected $_exceptionLog;

	public function __construct()
	{
		$this->_extractionPaths = array(
			'setErrorCode' => 'string(x:ErrorCode)',
			'setErrorDescription' => 'string(x:ErrorDescription)',
		);
		$this->_optionalExtractionPaths = array(
			'setPrimaryLangId' => 'x:PrimaryLangId',
			'setOrderId' => 'x:OrderId',
			'setStoreId' => 'x:StoreId',
			'setExceptionLog' => 'x:ExceptionLog',
		);
	}

	public function getPrimaryLangId()
	{
		return $this->_primaryLangId;
	}

	public function setPrimaryLangId($primaryLangId)
	{
		$this->_primaryLangId = $primaryLangId;
		return $this;
	}

	public function getOrderId()
	{
		return $this->_orderId;
	}

	public function setOrderId($orderId)
	{
		$this->_orderId = $orderId;
		return $this;
	}

	public function getStoreId()
	{
		return $this->_storeId;
	}

	public function setStoreId($storeId)
	{
		$this->_storeId = $storeId;
		return $this;
	}

	public function getErrorCode()
	{
		return $this->_errorCode;
	}

	public function setErrorCode($errorCode)
	{
		$this->_errorCode = $errorCode;
		return $this;
	}

	public function getErrorDescription()
	{
		return $this->_errorDescription;
	}

	public function setErrorDescription($errorDescription)
	{
		$this->_errorDescription = $errorDescription;
		return $this;
	}

	public function getExceptionLog()
	{
		return $this->_exceptionLog;
	}

	public function setExceptionLog($exceptionLog)
	{
		$this->_exceptionLog = $exceptionLog;
		return $this;
	}

	protected function _getSchemaFile()
	{
		return $this->_getSchemaDir() . self::XSD;
	}

	protected function _getXmlNamespace()
	{
		return static::XML_NS;
	}

	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	protected function _serializeContents()
	{
		return $this->_serializeOptionalValue('PrimaryLangId', $this->getPrimaryLangId())
			. $this->_serializeOptionalValue('OrderId', $this->getOrderId())
			. $this->_serializeOptionalValue('StoreId', $this->getStoreId())
			. $this->_serializeNode('ErrorCode', $this->getErrorCode())
			. $this->_serializeNode('ErrorDescription', $this->getErrorDescription())
			. $this->_serializeOptionalValue('ExceptionLog', $this->getExceptionLog());
	}
}
