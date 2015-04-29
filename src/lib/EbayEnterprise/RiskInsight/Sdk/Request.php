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

class EbayEnterprise_RiskInsight_Sdk_Request
	extends EbayEnterprise_RiskInsight_Sdk_Payload_Top
	implements EbayEnterprise_RiskInsight_Sdk_IRequest
{
	/** @var string */
	protected $_primaryLangId;
	/** @var EbayEnterprise_RiskInsight_Sdk_IOrder */
	protected $_order;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->setOrder($this->_buildPayloadForModel(static::ORDER_MODEL));
		$this->_extractionPaths = array(
			'setPrimaryLangId' => 'string(x:PrimaryLangId)',
		);
		$this->_subpayloadExtractionPaths = array(
			'setOrder' => 'x:Order',
		);
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_IRequest::getPrimaryLangId()
	 */
	public function getPrimaryLangId()
	{
		return $this->_primaryLangId;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_IRequest::setPrimaryLangId()
	 */
	public function setPrimaryLangId($primaryLangId)
	{
		$this->_primaryLangId = $primaryLangId;
		return $this;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_IRequest::getOrder()
	 */
	public function getOrder()
	{
		return $this->_order;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_IRequest::setOrder()
	 */
	public function setOrder(EbayEnterprise_RiskInsight_Sdk_IOrder $order)
	{
		$this->_order = $order;
		return $this;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_Payload_Top::_getSchemaFile()
	 */
	protected function _getSchemaFile()
	{
		return $this->_getSchemaDir() . self::XSD;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_Payload::_getXmlNamespace()
	 */
	protected function _getXmlNamespace()
	{
		return static::XML_NS;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_Payload::_getRootNodeName()
	 */
	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Sdk_Payload::_serializeContents()
	 */
	protected function _serializeContents()
	{
		return $this->_serializeNode('PrimaryLangId', $this->getPrimaryLangId())
			. $this->getOrder()->serialize();
	}
}
