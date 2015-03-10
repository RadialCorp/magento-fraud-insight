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

class EbayEnterprise_RiskInsight_Model_Line_Item
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_Line_IItem
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

	public function __construct()
	{
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

	public function getLineItemId()
	{
		return $this->_lineItemId;
	}

	public function setLineItemId($lineItemId)
	{
		$this->_lineItemId = $lineItemId;
		return $this;
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

	public function getProductId()
	{
		return $this->_productId;
	}

	public function setProductId($productId)
	{
		$this->_productId = $productId;
		return $this;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function setDescription($description)
	{
		$this->_description = $description;
		return $this;
	}

	public function getUnitCost()
	{
		return $this->_unitCost;
	}

	public function setUnitCost($unitCost)
	{
		$this->_unitCost = $unitCost;
		return $this;
	}

	public function getUnitCurrencyCode()
	{
		return $this->_unitCurrencyCode;
	}

	public function setUnitCurrencyCode($unitCurrencyCode)
	{
		$this->_unitCurrencyCode = $unitCurrencyCode;
		return $this;
	}

	public function getQuantity()
	{
		return $this->_quantity;
	}

	public function setQuantity($quantity)
	{
		$this->_quantity = $quantity;
		return $this;
	}

	public function getCategory()
	{
		return $this->_category;
	}

	public function setCategory($category)
	{
		$this->_category = $category;
		return $this;
	}

	public function getPromoCode()
	{
		return $this->_promoCode;
	}

	public function setPromoCode($promoCode)
	{
		$this->_promoCode = $promoCode;
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
		return $this->_serializeOptionalValue('ProductId', $this->getProductId())
			. $this->_serializeOptionalValue('Description', $this->getDescription())
			. $this->_serializeNode('UnitCost', $this->getUnitCost())
			. $this->_serializeNode('UnitCurrencyCode', $this->getUnitCurrencyCode())
			. $this->_serializeNode('Quantity', $this->getQuantity())
			. $this->_serializeOptionalValue('Category', $this->getCategory())
			. $this->_serializeOptionalValue('PromoCode', $this->getPromoCode());
	}

	protected function _getRootAttributes()
	{
		return array(
			'LineItemId' => $this->getLineItemId(),
			'ShipmentId' => $this->getShipmentId(),
		);
	}
}
