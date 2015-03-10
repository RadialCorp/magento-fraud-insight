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

class EbayEnterprise_RiskInsight_Model_Order
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_IOrder
{
	/** @var string */
	protected $_orderId;
	/** @var string */
	protected $_orderSource;
	/** @var DateTime */
	protected $_orderDate;
	/** @var string */
	protected $_storeId;
	/** @var EbayEnterprise_RiskInsight_Model_Shipping_List */
	protected $_shippingList;
	/** @var EbayEnterprise_RiskInsight_Model_Line_Items */
	protected $_lineItems;
	/** @var EbayEnterprise_RiskInsight_Model_Payments */
	protected $_formOfPayments;
	/** @var EbayEnterprise_RiskInsight_Model_Total */
	protected $_totalCost;
	/** @var EbayEnterprise_RiskInsight_Model_Device_Info */
	protected $_deviceInfo;

	public function __construct()
	{
		$this->setShippingList($this->_buildPayloadForModel(static::SHIPPING_LIST_MODEL));
		$this->setLineItems($this->_buildPayloadForModel(static::LINE_ITEMS_MODEL));
		$this->setFormOfPayments($this->_buildPayloadForModel(static::PAYMENTS_MODEL));
		$this->setTotalCost($this->_buildPayloadForModel(static::TOTAL_MODEL));
		$this->setDeviceInfo($this->_buildPayloadForModel(static::DEVICE_INFO_MODEL));
		$this->_extractionPaths = array(
			'setOrderId' => 'string(x:OrderId)',
			'setStoreId' => 'string(x:StoreId)',
		);
		$this->_optionalExtractionPaths = array(
			'setOrderSource' => 'x:OrderSource',
		);
		$this->_dateTimeExtractionPaths = array(
			'setOrderDate' => 'string(x:OrderDate)',
		);
		$this->_subpayloadExtractionPaths = array(
			'setShippingList' => 'x:ShippingList',
			'setLineItems' => 'x:LineItems',
			'setFormOfPayments' => 'x:FormOfPayments',
			'setTotalCost' => 'x:TotalCost',
			'setDeviceInfo' => 'x:DeviceInfo',
		);
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

	public function getOrderSource()
	{
		return $this->_orderSource;
	}

	public function setOrderSource($orderSource)
	{
		$this->_orderSource = $orderSource;
		return $this;
	}

	public function getOrderDate()
	{
		return $this->_orderDate ?: new DateTime();
	}

	public function setOrderDate(DateTime $orderDate)
	{
		$this->_orderDate = $orderDate;
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

	public function getShippingList()
	{
		return $this->_shippingList;
	}

	public function setShippingList(EbayEnterprise_RiskInsight_Model_Shipping_IList $shippingList)
	{
		$this->_shippingList = $shippingList;
		return $this;
	}

	public function getLineItems()
	{
		return $this->_lineItems;
	}

	public function setLineItems(EbayEnterprise_RiskInsight_Model_Line_IItems $lineItems)
	{
		$this->_lineItems = $lineItems;
		return $this;
	}

	public function getFormOfPayments()
	{
		return $this->_formOfPayments;
	}

	public function setFormOfPayments(EbayEnterprise_RiskInsight_Model_IPayments $formOfPayments)
	{
		$this->_formOfPayments = $formOfPayments;
		return $this;
	}

	public function getTotalCost()
	{
		return $this->_totalCost;
	}

	public function setTotalCost(EbayEnterprise_RiskInsight_Model_ITotal $totalCost)
	{
		$this->_totalCost = $totalCost;
		return $this;
	}

	public function getDeviceInfo()
	{
		return $this->_deviceInfo;
	}

	public function setDeviceInfo(EbayEnterprise_RiskInsight_Model_Device_IInfo $deviceInfo)
	{
		$this->_deviceInfo = $deviceInfo;
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
		return $this->_serializeNode('OrderId', $this->getOrderId())
			. $this->_serializeOptionalValue('OrderSource', $this->getOrderSource())
			. $this->_serializeNode('OrderDate', $this->getOrderDate()->format('c'))
			. $this->_serializeNode('StoreId', $this->getStoreId())
			. $this->getShippingList()->serialize()
			. $this->getLineItems()->serialize()
			. $this->getFormOfPayments()->serialize()
			. $this->getTotalCost()->serialize()
			. $this->getDeviceInfo()->serialize();
	}
}
