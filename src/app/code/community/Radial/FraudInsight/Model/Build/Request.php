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

class Radial_FraudInsight_Model_Build_Request
	extends Radial_FraudInsight_Model_Abstract
	implements Radial_FraudInsight_Model_Build_IRequest
{
	/** @var Radial_FraudInsight_Sdk_IPayload */
	protected $_request;
	/** @var Radial_FraudInsight_Model_Risk_Insight */
	protected $_insight;
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Mage_Sales_Model_Quote */
	protected $_quote;
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;
	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;
	/** @var Mage_Catalog_Model_Product */
	protected $_product;
	/** @var string */
	protected $_shippingId;
	/** @var string */
	protected $_billingId;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'request' => Radial_FraudInsight_Sdk_IPayload
	 *                          - 'insight' => Radial_FraudInsight_Model_Risk_Insight
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'quote' => Mage_Sales_Model_Quote
	 *                          - 'helper' => Radial_FraudInsight_Helper_Data
	 *                          - 'config' => Radial_FraudInsight_Helper_Config
	 *                          - 'product' => Mage_Catalog_Model_Product
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_request, $this->_insight, $this->_order, $this->_quote, $this->_helper, $this->_config, $this->_product) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'request', $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Request')),
			$this->_nullCoalesce($initParams, 'insight', Mage::getModel('radial_fraudinsight/risk_insight')),
			$this->_nullCoalesce($initParams, 'order', $initParams['order']),
			$this->_nullCoalesce($initParams, 'quote', Mage::getModel('sales/quote')),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('radial_fraudinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('radial_fraudinsight/config')),
			$this->_nullCoalesce($initParams, 'product', Mage::getModel('catalog/product'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @param  Mage_Sales_Model_Quote
	 * @param  Radial_FraudInsight_Helper_Data
	 * @param  Radial_FraudInsight_Helper_Config
	 * @param  Mage_Catalog_Model_Product
	 * @return array
	 */
	protected function _checkTypes(
		Radial_FraudInsight_Sdk_IPayload $request,
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order,
		Mage_Sales_Model_Quote $quote,
		Radial_FraudInsight_Helper_Data $helper,
		Radial_FraudInsight_Helper_Config $config,
		Mage_Catalog_Model_Product $product
	) {
		return array($request, $insight, $order, $quote, $helper, $config, $product);
	}

	public function build()
	{
		$this->_buildRequest();
		return $this->_request;
	}

	/**
	 * @return string
	 */
	protected function _getOrderSource()
	{
		return $this->_config->getOrderSource() ?: $this->_helper->getOrderSourceByArea($this->_order);
	}

	/**
	 * @return string | null
	 */
	protected function _getPaymentTransactionDate()
	{
		$quote = $this->_quote->loadByIdWithoutStore($this->_order->getQuoteId());
		return $quote->getId() ? $this->_getPaymentCreatedDate($quote) : null;
	}

	/**
	 * @param  Mage_Sales_Model_Quote
	 * @return string | null
	 */
	protected function _getPaymentCreatedDate(Mage_Sales_Model_Quote $quote)
	{
		$payment = $quote->getPayment();
		return $payment ? $payment->getCreatedAt() : null;
	}

	/**
	 * @return array
	 */
	protected function _getHttpHeaders()
	{
		$headers = $this->_insight->getHttpHeaders();
		return $headers ? json_decode($headers, true) : array();
	}

	/**
	 * @return string | null
	 */
	protected function _getShippingId()
	{
		if (!$this->_shippingId) {
			$shippingAddress = $this->_order->getShippingAddress();
			$this->_shippingId = $shippingAddress ? $shippingAddress->getId() : null;
		}
		return $this->_shippingId;
	}

	/**
	 * @return string | null
	 */
	protected function _getBillingId()
	{
		if (!$this->_billingId) {
			$this->_billingId = $this->_order->getBillingAddress()->getId();
		}
		return $this->_billingId;
	}

	/**
	 * @param  Mage_Core_Model_Abstract
	 * @return string | null
	 */
	protected function _getItemCategory(Mage_Core_Model_Abstract $item)
	{
		$product = $this->_product->load($item->getProductId());
		return $product->getId() ? $this->_getCategoryName($product) : null;
	}

	/**
	 * Get category collection.
	 *
	 * @return Mage_Catalog_Model_Resource_Category_Collection
	 */
	protected function _getCategoryCollection()
	{
		return Mage::getResourceModel('catalog/category_collection')
			->addAttributeToSelect('name');
	}

	/**
	 * @param  Mage_Core_Model_Abstract
	 * @return string | null
	 */
	protected function _getCategoryName(Mage_Core_Model_Abstract $product)
	{
		$categoryName = '';
		$categories = $product->getCategoryCollection();
		$collection = $this->_getCategoryCollection();
		foreach ($categories as $category) {
			$pathArr = explode('/', $category->getPath());
			array_walk($pathArr, function(&$val) use ($collection) {
				$part = $collection->getItemById((int) $val);
				$val = $part ? $part->getName() : null;
			});
			$catString = implode('->', array_filter($pathArr));
			if ($catString) {
				$categoryName .= $this->_getCategoryDelimiter($categoryName) . $catString;
			}
		}
		return $categoryName;
	}

	/**
	 * @param string
	 */
	protected function _getCategoryDelimiter($categoryName)
	{
		return $categoryName ? ',' : '';
	}

	/**
	 * @return self
	 */
	protected function _buildRequest()
	{
		$this->_request->setPrimaryLangId($this->_helper->getLanguageCode());
		$this->_buildOrder($this->_request->getOrder());
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_IOrder
	 * @return self
	 */
	protected function _buildOrder(Radial_FraudInsight_Sdk_IOrder $subPayloadOrder)
	{
		$subPayloadOrder->setOrderId($this->_order->getIncrementId())
			->setOrderSource($this->_getOrderSource())
			->setOrderDate($this->_helper->getNewDateTime($this->_order->getCreatedAt()))
			->setStoreId($this->_config->getStoreId());

		$this->_buildShippingList($subPayloadOrder->getShippingList())
			->_buildLineItems($subPayloadOrder->getLineItems())
			->_buildFormOfPayments($subPayloadOrder->getFormOfPayments())
			->_buildTotalCost($subPayloadOrder->getTotalCost())
			->_buildDeviceInfo($subPayloadOrder->getDeviceInfo());
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Shipping_IList
	 * @return self
	 */
	protected function _buildShippingList(Radial_FraudInsight_Sdk_Shipping_IList $subPayloadShippingList)
	{
		$shipments = $this->_getOrderShippingData();
		foreach ($shipments as $shipment) {
			$subPayloadShipment = $subPayloadShippingList->getEmptyShipment();
			$this->_buildShipment($subPayloadShipment, $shipment['address'], $shipment['type']);
			$subPayloadShippingList->offsetSet($subPayloadShipment);
		}
		return $this;
	}

	/**
	 * When the order is virtual simply return virtual shipment data otherwise
	 * find out if the order has any items that are virtual to return a combination
	 * of both virtual and physical shipment data. However, if the order only
	 * has physical items simply return physical shipment data.
	 *
	 * @return array
	 */
	protected function _getOrderShippingData()
	{
		return $this->_order->getIsVirtual()
			? $this->_getVirtualOrderShippingData()
			: $this->_getPhysicalVirtualShippingData();
	}

	/**
	 * Determine if the order has an virtual items, if so,
	 * simply return a combination of physical and virtual shipment
	 * data. Otherwise, simply return physical shipment data.
	 *
	 * @return array
	 */
	protected function _getPhysicalVirtualShippingData()
	{
		return $this->_hasVirtualItems()
			? array_merge($this->_getPhysicalOrderShippingData(), $this->_getVirtualOrderShippingData())
			: $this->_getPhysicalOrderShippingData();
	}

	/**
	 * Returns virtual shipment data.
	 *
	 * @return array
	 */
	protected function _getVirtualOrderShippingData()
	{
		return array(array(
			'type' => static::VIRTUAL_SHIPMENT_TYPE,
			'address' => $this->_order->getBillingAddress(),
		));
	}

	/**
	 * Returns physical shipment data.
	 *
	 * @return array
	 */
	protected function _getPhysicalOrderShippingData()
	{
		return array(array(
			'type' => static::PHYSICAL_SHIPMENT_TYPE,
			'address' => $this->_order->getShippingAddress(),
		));
	}

	/**
	 * Returns true when the item is virtual otherwise false.
	 *
	 * @param  Mage_Sales_Model_Order_Item
	 * @return bool
	 */
	protected function _isItemVirtual(Mage_Sales_Model_Order_Item $item)
	{
		return ((int) $item->getIsVirtual() === 1);
	}

	/**
	 * Returns true when the passed in type is a physical shipment type
	 * otherwise false.
	 *
	 * @param  string
	 * @return bool
	 */
	protected function _isVirtualShipmentType($type)
	{
		return ($type !== static::PHYSICAL_SHIPMENT_TYPE);
	}

	/**
	 * Returns true if any items in the order is virtual, otherwise,
	 * return false.
	 *
	 * @return bool
	 */
	protected function _hasVirtualItems()
	{
		$hasVirtual = false;
		foreach ($this->_order->getAllItems() as $orderItem) {
			if ($this->_isItemVirtual($orderItem)) {
				$hasVirtual = true;
				break;
			}
		}
		return $hasVirtual;
	}

	/**
	 * Returns the billing id if the item is virtual otherwise returns
	 * the shipping id.
	 *
	 * @param  Mage_Sales_Model_Order_Item
	 * @return string
	 */
	protected function _getShipmentIdByItem(Mage_Sales_Model_Order_Item $item)
	{
		return $this->_isItemVirtual($item) ? $this->_getBillingId() : $this->_getShippingId();
	}

	/**
	 * Returns the virtual shipping method when the types is a virtual shipment
	 * otherwise returns the shipping method in the order.
	 *
	 * @param  string
	 * @return string
	 */
	protected function _getShippingMethodByType($type)
	{
		return $this->_isVirtualShipmentType($type)
			? static::VIRTUAL_SHIPPING_METHOD
			: $this->_order->getShippingMethod();
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Line_IItems
	 * @return self
	 */
	protected function _buildLineItems(Radial_FraudInsight_Sdk_Line_IItems $subPayloadLineItems)
	{
		foreach ($this->_order->getAllItems() as $orderItem) {
			$subPayloadLineItem = $subPayloadLineItems->getEmptyLineItem();
			$this->_buildLineItem($subPayloadLineItem, $orderItem);
			$subPayloadLineItems->offsetSet($subPayloadLineItem);
		}
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_IPayments
	 * @return self
	 */
	protected function _buildFormOfPayments(Radial_FraudInsight_Sdk_IPayments $subPayloadFormOfPayments)
	{
		$orderBillingAddress = $this->_order->getBillingAddress();
		$orderPayment = $this->_order->getPayment();
		if ($orderBillingAddress && $orderPayment) {
			$subPayloadPayment = $subPayloadFormOfPayments->getEmptyPayment();
			$this->_buildPayment($subPayloadPayment, $orderBillingAddress, $orderPayment);
			$subPayloadFormOfPayments->offsetSet($subPayloadPayment);
		}
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_ITotal
	 * @return self
	 */
	protected function _buildTotalCost(Radial_FraudInsight_Sdk_ITotal $subPayloadTotalCost)
	{
		$subPayloadCostTotals = $subPayloadTotalCost->getCostTotals();
		$subPayloadCostTotals->setCurrencyCode($this->_order->getBaseCurrencyCode())
			->setAmountBeforeTax($this->_order->getSubtotal())
			->setAmountAfterTax($this->_order->getGrandTotal());
		$subPayloadTotalCost->setCostTotals($subPayloadCostTotals);
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Device_IInfo
	 * @return self
	 */
	protected function _buildDeviceInfo(Radial_FraudInsight_Sdk_Device_IInfo $subPayloadDeviceInfo)
	{
		$subPayloadDeviceInfo->setDeviceIP($this->_order->getRemoteIp());
		$this->_buildHttpHeaders($subPayloadDeviceInfo->getHttpHeaders());
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_IShipment
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @param  string
	 * @return self
	 */
	protected function _buildShipment(
		Radial_FraudInsight_Sdk_IShipment $subPayloadShipment,
		Mage_Customer_Model_Address_Abstract $orderShippingAddress,
		$type
	)
	{
		$subPayloadShipment->setShipmentId($orderShippingAddress->getId())
			->setShippingMethod($this->_getShippingMethodByType($type));

		$this->_buildPersonName($subPayloadShipment->getPersonName(), $orderShippingAddress);
		if ($this->_isVirtualShipmentType($type)) {
			$subPayloadShipment->setEmail($this->_order->getCustomerEmail());
		} else {
			$this->_buildTelephone($subPayloadShipment->getTelephone(), $orderShippingAddress)
				->_buildAddress($subPayloadShipment->getAddress(), $orderShippingAddress);
		}
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Person_IName
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @return self
	 */
	protected function _buildPersonName(
		Radial_FraudInsight_Sdk_Person_IName $subPayloadPersonName,
		Mage_Customer_Model_Address_Abstract $orderAddress
	)
	{
		$subPayloadPersonName->setLastName($orderAddress->getFirstname())
			->setMiddleName($orderAddress->getMiddlename())
			->setFirstName($orderAddress->getLastname());
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_ITelephone
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @return self
	 */
	protected function _buildTelephone(
		Radial_FraudInsight_Sdk_ITelephone $subPayloadTelephone,
		Mage_Customer_Model_Address_Abstract $orderAddress
	)
	{
		$subPayloadTelephone->setCountryCode(null)
			->setAreaCode(null)
			->setNumber($orderAddress->getTelephone())
			->setExtension(null);
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_IAddress
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @return self
	 */
	protected function _buildAddress(
		Radial_FraudInsight_Sdk_IAddress $subPayloadAddress,
		Mage_Customer_Model_Address_Abstract $orderAddress
	)
	{
		$subPayloadAddress->setLineA($orderAddress->getStreet(1))
			->setLineB($orderAddress->getStreet(2))
			->setLineC($orderAddress->getStreet(3))
			->setLineD($orderAddress->getStreet(4))
			->setCity($orderAddress->getCity())
			->setPostalCode($orderAddress->getPostcode())
			->setMainDivisionCode($orderAddress->getRegionCode())
			->setCountryCode($orderAddress->getCountryId());
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Line_IItem
	 * @param  Mage_Core_Model_Abstract
	 * @return self
	 */
	protected function _buildLineItem(
		Radial_FraudInsight_Sdk_Line_IItem $subPayloadLineItem,
		Mage_Core_Model_Abstract $orderItem
	)
	{
		$subPayloadLineItem->setLineItemId($orderItem->getId())
			->setShipmentId($this->_getShipmentIdByItem($orderItem))
			->setProductId($orderItem->getSku())
			->setDescription($orderItem->getName())
			->setUnitCost($orderItem->getPrice())
			->setUnitCurrencyCode($this->_order->getBaseCurrencyCode())
			->setQuantity((int) $orderItem->getQtyOrdered())
			->setCategory($this->_getItemCategory($orderItem))
			->setPromoCode($this->_order->getCouponCode());
		return $this;
	}

	/**
	 * @return Radial_FraudInsight_Model_Payment_IAdapter
	 */
	protected function _getPaymentAdapter()
	{
		return Mage::getModel('radial_fraudinsight/payment_adapter', array(
			'order' => $this->_order
		));
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Line_IItem
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return self
	 */
	protected function _buildPayment(
		Radial_FraudInsight_Sdk_IPayment $subPayloadPayment,
		Mage_Customer_Model_Address_Abstract $orderBillingAddress,
		Mage_Sales_Model_Order_Payment $orderPayment
	)
	{
		$subPayloadPayment->setEmail($this->_order->getCustomerEmail())
			->setPaymentTransactionDate($this->_helper->getNewDateTime($this->_getPaymentTransactionDate()))
			->setCurrencyCode($this->_order->getBaseCurrencyCode())
			->setAmount($orderPayment->getAmountAuthorized())
			->setTotalAuthAttemptCount(null);

		$paymentAdapterType = $this->_getPaymentAdapter()->getAdapter();
		$this->_buildPaymentCard($subPayloadPayment->getPaymentCard(), $paymentAdapterType)
			->_buildPersonName($subPayloadPayment->getPersonName(), $orderBillingAddress)
			->_buildTelephone($subPayloadPayment->getTelephone(), $orderBillingAddress)
			->_buildAddress($subPayloadPayment->getAddress(), $orderBillingAddress)
			->_buildTransactionResponses($subPayloadPayment->getTransactionResponses(), $paymentAdapterType);
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Payment_ICard
	 * @param  Radial_FraudInsight_Model_Payment_Adapter_IType
	 * @return self
	 */
	protected function _buildPaymentCard(
		Radial_FraudInsight_Sdk_Payment_ICard $subPayloadCard,
		Radial_FraudInsight_Model_Payment_Adapter_IType $paymentAdapterType
	)
	{
		$subPayloadCard->setCardHolderName($paymentAdapterType->getExtractCardHolderName())
			->setPaymentAccountUniqueId($paymentAdapterType->getExtractPaymentAccountUniqueId())
			->setIsToken($paymentAdapterType->getExtractIsToken())
			->setPaymentAccountBin($paymentAdapterType->getExtractPaymentAccountBin())
			->setExpireDate($paymentAdapterType->getExtractExpireDate())
			->setCardType($paymentAdapterType->getExtractCardType());
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Transaction_IResponses
	 * @param  Radial_FraudInsight_Model_Payment_Adapter_IType
	 * @return self
	 */
	protected function _buildTransactionResponses(
		Radial_FraudInsight_Sdk_Transaction_IResponses $subPayloadResponses,
		Radial_FraudInsight_Model_Payment_Adapter_IType $paymentAdapterType
	)
	{
		$transactionResponses = (array) $paymentAdapterType->getExtractTransactionResponses();
		foreach ($transactionResponses as $transaction) {
			$subPayloadResponse = $subPayloadResponses->getEmptyTransactionResponse();
			$this->_buildTransactionResponse($subPayloadResponse, $transaction['response'], $transaction['type']);
			$subPayloadResponses->offsetSet($subPayloadResponse);
		}
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Transaction_IResponse
	 * @param  string
	 * @param  string
	 * @return self
	 */
	protected function _buildTransactionResponse(
		Radial_FraudInsight_Sdk_Transaction_IResponse $subPayloadResponse,
		$response,
		$type
	)
	{
		$subPayloadResponse->setResponse($response)
			->setResponseType($type);
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Http_IHeaders
	 * @return self
	 */
	protected function _buildHttpHeaders(Radial_FraudInsight_Sdk_Http_IHeaders $subPayloadHttpHeaders)
	{
		foreach ($this->_getHttpHeaders() as $name => $message) {
			$subPayloadHttpHeader = $subPayloadHttpHeaders->getEmptyHttpHeader();
			$this->_buildHttpHeader($subPayloadHttpHeader, $name, $message);
			$subPayloadHttpHeaders->offsetSet($subPayloadHttpHeader);
		}
		return $this;
	}

	/**
	 * @param  Radial_FraudInsight_Sdk_Http_IHeader
	 * @param  string
	 * @param  string
	 * @return self
	 */
	protected function _buildHttpHeader(Radial_FraudInsight_Sdk_Http_IHeader $subPayloadHttpHeader, $name, $message)
	{
		$subPayloadHttpHeader->setHeader($message)
			->setName($name);
		return $this;
	}
}
