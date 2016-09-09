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

class Radial_FraudInsight_Sdk_Shipment
	extends Radial_FraudInsight_Sdk_Info
	implements Radial_FraudInsight_Sdk_IShipment
{
	/** @var string */
	protected $_shipmentId;
	/** @var string */
	protected $_shippingMethod;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
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

	/**
	 * @see Radial_FraudInsight_Sdk_IShipment::getShipmentId()
	 */
	public function getShipmentId()
	{
		return $this->_shipmentId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IShipment::setShipmentId()
	 */
	public function setShipmentId($shipmentId)
	{
		$this->_shipmentId = $shipmentId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IShipment::getShippingMethod()
	 */
	public function getShippingMethod()
	{
		return $this->_shippingMethod;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IShipment::setShippingMethod()
	 */
	public function setShippingMethod($shippingMethod)
	{
		$this->_shippingMethod = $shippingMethod;
		return $this;
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
		return $this->getPersonName()->serialize()
			. $this->_serializeOptionalValue('Email', $this->getEmail())
			. $this->getTelephone()->serialize()
			. $this->getAddress()->serialize()
			. $this->_serializeNode('ShippingMethod', $this->getShippingMethod());
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootAttributes()
	 */
	protected function _getRootAttributes()
	{
		return array(
			'ShipmentId' => $this->getShipmentId(),
		);
	}
}
