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

class EbayEnterprise_RiskInsight_Model_Request
	extends EbayEnterprise_RiskInsight_Model_Payload_Top
	implements EbayEnterprise_RiskInsight_Model_IRequest
{
	/** @var string */
	protected $_primaryLangId;
	/** @var EbayEnterprise_RiskInsight_Model_IOrder */
	protected $_order;

	public function __construct()
	{
		$this->setOrder($this->_buildPayloadForModel(static::ORDER_MODEL));
		$this->_extractionPaths = array(
			'setPrimaryLangId' => 'string(x:PrimaryLangId)',
		);
		$this->_subpayloadExtractionPaths = array(
			'setOrder' => 'x:Order',
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

	public function getOrder()
	{
		return $this->_order;
	}

	public function setOrder(EbayEnterprise_RiskInsight_Model_IOrder $order)
	{
		$this->_order = $order;
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
		return $this->_serializeNode('PrimaryLangId', $this->getPrimaryLangId())
			. $this->getOrder()->serialize();
	}
}
