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

class EbayEnterprise_RiskInsight_Model_Http_Header
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_Http_IHeader
{
	/** @var string */
	protected $_header;
	/** @var string */
	protected $_name;

	public function __construct()
	{
		$this->_extractionPaths = array(
			'setHeader' => 'string(.)',
			'setName' => 'string(@name)',
		);
	}

	public function getHeader()
	{
		return $this->_header;
	}

	public function setHeader($header)
	{
		$this->_header = $header;
		return $this;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function setName($name)
	{
		$this->_name = $name;
		return $this;
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
		return $this->getHeader();
	}

	protected function _getRootAttributes()
	{
		return array(
			'name' => $this->getName(),
		);
	}
}
