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

class Radial_FraudInsight_Sdk_Line_Item
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_Line_IItem
{
	/** @var string */
	protected $_lineItemId;
	/** @var string */
	protected $_shipmentId;
	/** @var string */
	protected $_productId;
	/** @var string */
	protected $_description;
	/** @var float */
	protected $_unitCost;
	/** @var string */
	protected $_unitCurrencyCode;
	/** @var int */
	protected $_quantity;
	/** @var string */
	protected $_category;
	/** @var string */
	protected $_promoCode;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->_extractionPaths = array(
			'setLineItemId' => 'string(@LineItemId)',
			'setShipmentId' => 'string(@ShipmentId)',
			'setUnitCost' => 'number(x:UnitCost)',
			'setUnitCurrencyCode' => 'string(x:UnitCurrencyCode)',
			'setQuantity' => 'number(x:Quantity)',
		);
		$this->_optionalExtractionPaths = array(
			'setProductId' => 'x:ProductId',
			'setDescription' => 'x:Description',
			'setCategory' => 'x:Category',
			'setPromoCode' => 'x:PromoCode',
		);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getLineItemId()
	 */
	public function getLineItemId()
	{
		return $this->_lineItemId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setLineItemId()
	 */
	public function setLineItemId($lineItemId)
	{
		$this->_lineItemId = $lineItemId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getShipmentId()
	 */
	public function getShipmentId()
	{
		return $this->_shipmentId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setShipmentId()
	 */
	public function setShipmentId($shipmentId)
	{
		$this->_shipmentId = $shipmentId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getProductId()
	 */
	public function getProductId()
	{
		return $this->_productId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setProductId()
	 */
	public function setProductId($productId)
	{
		$this->_productId = $productId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getDescription()
	 */
	public function getDescription()
	{
		return $this->_description;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setDescription()
	 */
	public function setDescription($description)
	{
		$this->_description = $description;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getUnitCost()
	 */
	public function getUnitCost()
	{
		return $this->_unitCost;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setUnitCost()
	 */
	public function setUnitCost($unitCost)
	{
		$this->_unitCost = $unitCost;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getUnitCurrencyCode()
	 */
	public function getUnitCurrencyCode()
	{
		return $this->_unitCurrencyCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setUnitCurrencyCode()
	 */
	public function setUnitCurrencyCode($unitCurrencyCode)
	{
		$this->_unitCurrencyCode = $unitCurrencyCode;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getQuantity()
	 */
	public function getQuantity()
	{
		return $this->_quantity;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setQuantity()
	 */
	public function setQuantity($quantity)
	{
		$this->_quantity = $quantity;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getCategory()
	 */
	public function getCategory()
	{
		return $this->_category;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setCategory()
	 */
	public function setCategory($category)
	{
		$this->_category = $category;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::getPromoCode()
	 */
	public function getPromoCode()
	{
		return $this->_promoCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Line_IItem::setPromoCode()
	 */
	public function setPromoCode($promoCode)
	{
		$this->_promoCode = $promoCode;
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
		return $this->_serializeOptionalValue('ProductId', $this->getProductId())
			. $this->_serializeOptionalValue('Description', $this->getDescription())
			. $this->_serializeAmountNode('UnitCost', $this->getUnitCost())
			. $this->_serializeNode('UnitCurrencyCode', $this->getUnitCurrencyCode())
			. $this->_serializeNode('Quantity', $this->getQuantity())
			. $this->_serializeOptionalValue('Category', $this->getCategory())
			. $this->_serializeOptionalValue('PromoCode', $this->getPromoCode());
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootAttributes()
	 */
	protected function _getRootAttributes()
	{
		return array(
			'LineItemId' => $this->getLineItemId(),
			'ShipmentId' => $this->getShipmentId(),
		);
	}
}
