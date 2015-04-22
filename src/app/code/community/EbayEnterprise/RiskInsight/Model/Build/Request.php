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

class EbayEnterprise_RiskInsight_Model_Build_Request
	implements EbayEnterprise_RiskInsight_Model_Build_IRequest
{
	/** @var EbayEnterprise_RiskInsight_Model_IPayload */
	protected $_request;
	/** @var EbayEnterprise_RiskInsight_Model_Risk_Insight */
	protected $_insight;
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Mage_Sales_Model_Quote */
	protected $_quote;
	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helper;
	/** @var EbayEnterprise_RiskInsight_Helper_Config */
	protected $_config;
	/** @var Mage_Catalog_Model_Product */
	protected $_product;
	/** @var string */
	protected $_shippingId;
	/** @var string */
	protected $_billingId;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'request' => EbayEnterprise_RiskInsight_Model_IPayload
	 *                          - 'insight' => EbayEnterprise_RiskInsight_Model_Risk_Insight
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'quote' => Mage_Sales_Model_Quote
	 *                          - 'helper' => EbayEnterprise_RiskInsight_Helper_Data
	 *                          - 'config' => EbayEnterprise_RiskInsight_Helper_Config
	 *                          - 'product' => Mage_Catalog_Model_Product
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_request, $this->_insight, $this->_order, $this->_quote, $this->_helper, $this->_config, $this->_product) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'request', Mage::getModel('ebayenterprise_riskinsight/request')),
			$this->_nullCoalesce($initParams, 'insight', Mage::getModel('ebayenterprise_riskinsight/risk_insight')),
			$this->_nullCoalesce($initParams, 'order', $initParams['order']),
			$this->_nullCoalesce($initParams, 'quote', Mage::getModel('sales/quote')),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('ebayenterprise_riskinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('ebayenterprise_riskinsight/config')),
			$this->_nullCoalesce($initParams, 'product', Mage::getModel('catalog/product'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  EbayEnterprise_RiskInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @param  Mage_Sales_Model_Quote
	 * @param  EbayEnterprise_RiskInsight_Helper_Data
	 * @param  EbayEnterprise_RiskInsight_Helper_Config
	 * @param  Mage_Catalog_Model_Product
	 * @return array
	 */
	protected function _checkTypes(
		EbayEnterprise_RiskInsight_Model_IPayload $request,
		EbayEnterprise_RiskInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order,
		Mage_Sales_Model_Quote $quote,
		EbayEnterprise_RiskInsight_Helper_Data $helper,
		EbayEnterprise_RiskInsight_Helper_Config $config,
		Mage_Catalog_Model_Product $product
	) {
		return array($request, $insight, $order, $quote, $helper, $config, $product);
	}

	/**
	 * Return the value at field in array if it exists. Otherwise, use the default value.
	 *
	 * @param  array
	 * @param  string | int $field Valid array key
	 * @param  mixed
	 * @return mixed
	 */
	protected function _nullCoalesce(array $arr, $field, $default)
	{
		return isset($arr[$field]) ? $arr[$field] : $default;
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
		return $this->_config->getOrderSource() ?: $this->_helper->getOrderSourceByArea();
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
	 * @param  EbayEnterprise_RiskInsight_Model_IOrder
	 * @return self
	 */
	protected function _buildOrder(EbayEnterprise_RiskInsight_Model_IOrder $subPayloadOrder)
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
	 * @param  EbayEnterprise_RiskInsight_Model_Shipping_IList
	 * @return self
	 */
	protected function _buildShippingList(EbayEnterprise_RiskInsight_Model_Shipping_IList $subPayloadShippingList)
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
	 * @param  EbayEnterprise_RiskInsight_Model_Line_IItems
	 * @return self
	 */
	protected function _buildLineItems(EbayEnterprise_RiskInsight_Model_Line_IItems $subPayloadLineItems)
	{
		foreach ($this->_order->getAllItems() as $orderItem) {
			$subPayloadLineItem = $subPayloadLineItems->getEmptyLineItem();
			$this->_buildLineItem($subPayloadLineItem, $orderItem);
			$subPayloadLineItems->offsetSet($subPayloadLineItem);
		}
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IPayments
	 * @return self
	 */
	protected function _buildFormOfPayments(EbayEnterprise_RiskInsight_Model_IPayments $subPayloadFormOfPayments)
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
	 * @param  EbayEnterprise_RiskInsight_Model_ITotal
	 * @return self
	 */
	protected function _buildTotalCost(EbayEnterprise_RiskInsight_Model_ITotal $subPayloadTotalCost)
	{
		$subPayloadCostTotals = $subPayloadTotalCost->getCostTotals();
		$subPayloadCostTotals->setCurrencyCode($this->_order->getBaseCurrencyCode())
			->setAmountBeforeTax($this->_order->getSubtotal())
			->setAmountAfterTax($this->_order->getGrandTotal());
		$subPayloadTotalCost->setCostTotals($subPayloadCostTotals);
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Device_IInfo
	 * @return self
	 */
	protected function _buildDeviceInfo(EbayEnterprise_RiskInsight_Model_Device_IInfo $subPayloadDeviceInfo)
	{
		$subPayloadDeviceInfo->setDeviceIP($this->_order->getRemoteIp());
		$this->_buildHttpHeaders($subPayloadDeviceInfo->getHttpHeaders());
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IShipment
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @param  string
	 * @return self
	 */
	protected function _buildShipment(
		EbayEnterprise_RiskInsight_Model_IShipment $subPayloadShipment,
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
	 * @param  EbayEnterprise_RiskInsight_Model_Person_IName
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @return self
	 */
	protected function _buildPersonName(
		EbayEnterprise_RiskInsight_Model_Person_IName $subPayloadPersonName,
		Mage_Customer_Model_Address_Abstract $orderAddress
	)
	{
		$subPayloadPersonName->setLastName($orderAddress->getFirstname())
			->setMiddleName($orderAddress->getMiddlename())
			->setFirstName($orderAddress->getLastname());
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_ITelephone
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @return self
	 */
	protected function _buildTelephone(
		EbayEnterprise_RiskInsight_Model_ITelephone $subPayloadTelephone,
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
	 * @param  EbayEnterprise_RiskInsight_Model_IAddress
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @return self
	 */
	protected function _buildAddress(
		EbayEnterprise_RiskInsight_Model_IAddress $subPayloadAddress,
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
	 * @param  EbayEnterprise_RiskInsight_Model_Line_IItem
	 * @param  Mage_Core_Model_Abstract
	 * @return self
	 */
	protected function _buildLineItem(
		EbayEnterprise_RiskInsight_Model_Line_IItem $subPayloadLineItem,
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
	 * @return EbayEnterprise_RiskInsight_Model_Payment_IAdapter
	 */
	protected function _getPaymentAdapter()
	{
		return Mage::getModel('ebayenterprise_riskinsight/payment_adapter', array(
			'order' => $this->_order
		));
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Line_IItem
	 * @param  Mage_Customer_Model_Address_Abstract
	 * @param  Mage_Sales_Model_Order_Payment
	 * @return self
	 */
	protected function _buildPayment(
		EbayEnterprise_RiskInsight_Model_IPayment $subPayloadPayment,
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
	 * @param  EbayEnterprise_RiskInsight_Model_Payment_ICard
	 * @param  EbayEnterprise_RiskInsight_Model_Payment_Adapter_IType
	 * @return self
	 */
	protected function _buildPaymentCard(
		EbayEnterprise_RiskInsight_Model_Payment_ICard $subPayloadCard,
		EbayEnterprise_RiskInsight_Model_Payment_Adapter_IType $paymentAdapterType
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
	 * @param  EbayEnterprise_RiskInsight_Model_Transaction_IResponses
	 * @param  EbayEnterprise_RiskInsight_Model_Payment_Adapter_IType
	 * @return self
	 */
	protected function _buildTransactionResponses(
		EbayEnterprise_RiskInsight_Model_Transaction_IResponses $subPayloadResponses,
		EbayEnterprise_RiskInsight_Model_Payment_Adapter_IType $paymentAdapterType
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
	 * @param  EbayEnterprise_RiskInsight_Model_Transaction_IResponse
	 * @param  string
	 * @param  string
	 * @return self
	 */
	protected function _buildTransactionResponse(
		EbayEnterprise_RiskInsight_Model_Transaction_IResponse $subPayloadResponse,
		$response,
		$type
	)
	{
		$subPayloadResponse->setResponse($response)
			->setResponseType($type);
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Http_IHeaders
	 * @return self
	 */
	protected function _buildHttpHeaders(EbayEnterprise_RiskInsight_Model_Http_IHeaders $subPayloadHttpHeaders)
	{
		foreach ($this->_getHttpHeaders() as $name => $message) {
			$subPayloadHttpHeader = $subPayloadHttpHeaders->getEmptyHttpHeader();
			$this->_buildHttpHeader($subPayloadHttpHeader, $name, $message);
			$subPayloadHttpHeaders->offsetSet($subPayloadHttpHeader);
		}
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Http_IHeader
	 * @param  string
	 * @param  string
	 * @return self
	 */
	protected function _buildHttpHeader(EbayEnterprise_RiskInsight_Model_Http_IHeader $subPayloadHttpHeader, $name, $message)
	{
		$subPayloadHttpHeader->setHeader($message)
			->setName($name);
		return $this;
	}
}
