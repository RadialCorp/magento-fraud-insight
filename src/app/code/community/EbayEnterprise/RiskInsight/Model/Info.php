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

class EbayEnterprise_RiskInsight_Model_Info
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_IInfo
{
	/** @var EbayEnterprise_RiskInsight_Model_Person_IName */
	protected $_personName;
	/** @var string */
	protected $_email;
	/** @var EbayEnterprise_RiskInsight_Model_ITelephone */
	protected $_telephone;
	/** @var EbayEnterprise_RiskInsight_Model_IAddress */
	protected $_address;

	public function getPersonName()
	{
		return $this->_personName;
	}

	public function setPersonName(EbayEnterprise_RiskInsight_Model_Person_IName $personName)
	{
		$this->_personName = $personName;
		return $this;
	}

	public function getEmail()
	{
		return $this->_email;
	}

	public function setEmail($email)
	{
		$this->_email = $email;
		return $this;
	}

	public function getTelephone()
	{
		return $this->_telephone;
	}

	public function setTelephone(EbayEnterprise_RiskInsight_Model_ITelephone $telephone)
	{
		$this->_telephone = $telephone;
		return $this;
	}

	public function getAddress()
	{
		return $this->_address;
	}

	public function setAddress(EbayEnterprise_RiskInsight_Model_IAddress $address)
	{
		$this->_address = $address;
		return $this;
	}

	protected function _getRootNodeName()
	{
		return '';
	}

	protected function _getXmlNamespace()
	{
		return '';
	}

	protected function _serializeContents()
	{
		return '';
	}
}
