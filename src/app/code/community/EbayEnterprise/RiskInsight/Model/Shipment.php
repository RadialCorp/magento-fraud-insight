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

class EbayEnterprise_RiskInsight_Model_Shipment
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_IShipment
{
	/** @var string */
	protected $_shipmentId;
	/** @var EbayEnterprise_RiskInsight_Model_Person_Name */
	protected $_personName;
	/** @var string */
	protected $_email;
	/** @var EbayEnterprise_RiskInsight_Model_Telephone */
	protected $_telephone;
	/** @var EbayEnterprise_RiskInsight_Model_Address */
	protected $_address;
	/** @var string */
	protected $_shippingMethod;

	public function __construct()
	{
		$this->setPersonName($this->_buildPayloadForModel(static::PERSON_NAME_MODEL));
		$this->setTelephone($this->_buildPayloadForModel(static::TELEPHONE_MODEL));
		$this->setAddress($this->_buildPayloadForModel(static::ADDRESS_MODEL));
		$this->_extractionPaths = array(
			'setShipmentId' => 'string(@ShipmentId)',
			'setShippingMethod' => 'string(x:ShippingMethod)',
		);
		$this->_optionalExtractionPaths = array(
			'setEmail' => 'x:Email',
		);
		$this->_subpayloadExtractionPaths = array(
			'setPersonName' => 'x:PersonName',
			'setTelephone' => 'x:Telephone',
			'setAddress' => 'x:Address',
		);
	}

	public function getShipmentId()
	{
		return $this->_shipmentId;
	}

	public function setShipmentId($shipmentId)
	{
		$this->_shipmentId = $shipmentId;
		return $this;
	}

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

	public function getShippingMethod()
	{
		return $this->_shippingMethod;
	}

	public function setShippingMethod($shippingMethod)
	{
		$this->_shippingMethod = $shippingMethod;
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
		return $this->getPersonName()->serialize()
			. $this->_serializeOptionalValue('Email', $this->getEmail())
			. $this->getTelephone()->serialize()
			. $this->getAddress()->serialize()
			. $this->_serializeNode('ShippingMethod', $this->getShippingMethod());
	}

	protected function _getRootAttributes()
	{
		return array(
			'ShipmentId' => $this->getShipmentId(),
		);
	}
}
