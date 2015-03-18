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
	/** @var EbayEnterprise_RiskInsight_Model_IPayload $_request */
	protected $_request;
	/** @var EbayEnterprise_RiskInsight_Model_Risk_Insight $_request */
	protected $_insight;
	/** @var Mage_Sales_Model_Order $_order */
	protected $_order;
	/** @var Mage_Sales_Model_Quote $_quote */
	protected $_quote;
	/** @var EbayEnterprise_RiskInsight_Helper_Data $_helper */
	protected $_helper;
	/** @var EbayEnterprise_RiskInsight_Helper_Config $_config*/
	protected $_config;
	/** @var Mage_Catalog_Model_Product $_product*/
	protected $_product;
	/** @var string */
	protected $_shipmentId;

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
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload $request
	 * @param  EbayEnterprise_RiskInsight_Model_Risk_Insight $insight
	 * @param  Mage_Sales_Model_Order $order
	 * @param  Mage_Sales_Model_Quote $quote
	 * @param  EbayEnterprise_RiskInsight_Helper_Data $helper
	 * @param  EbayEnterprise_RiskInsight_Helper_Config $config
	 * @param  Mage_Catalog_Model_Product $product
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
	 * @param  array $arr
	 * @param  string | int $field Valid array key
	 * @param  mixed $default
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
	 * @param  Mage_Sales_Model_Quote $quote
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
	 * Add the shipping address id to the class property '_shipmentId' when there's a shipment
	 * for the order, otherwise if the order is a virtual order without any shipment, just use
	 * the billing address id.
	 *
	 * @return string
	 */
	protected function _getShipmentId()
	{
		if (!$this->_shipmentId) {
			$shippingAddress = $this->_order->getShippingAddress();
			$this->_shipmentId = $shippingAddress ? $shippingAddress->getId() : $this->_order->getBillingAddress()->getId();
		}
		return $this->_shipmentId;
	}

	/**
	 * @param  Mage_Core_Model_Abstract $item
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
	 * @param  Mage_Core_Model_Abstract $item
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
	 * @param string $categoryName
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
	 * @param  EbayEnterprise_RiskInsight_Model_IOrder $subPayloadOrder
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
	 * @param  EbayEnterprise_RiskInsight_Model_Shipping_IList $subPayloadShippingList
	 * @return self
	 */
	protected function _buildShippingList(EbayEnterprise_RiskInsight_Model_Shipping_IList $subPayloadShippingList)
	{
		$orderShippingAddress = $this->_order->getShippingAddress();
		if ($orderShippingAddress) {
			$subPayloadShipment = $subPayloadShippingList->getEmptyShipment();
			$this->_buildShipment($subPayloadShipment, $orderShippingAddress);
			$subPayloadShippingList->offsetSet($subPayloadShipment);
		}
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Line_IItems $subPayloadLineItems
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
	 * @param  EbayEnterprise_RiskInsight_Model_IPayments $subPayloadFormOfPayments
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
	 * @param  EbayEnterprise_RiskInsight_Model_ITotal $subPayloadTotalCost
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
	 * @param  EbayEnterprise_RiskInsight_Model_Device_IInfo $subPayloadDeviceInfo
	 * @return self
	 */
	protected function _buildDeviceInfo(EbayEnterprise_RiskInsight_Model_Device_IInfo $subPayloadDeviceInfo)
	{
		$subPayloadDeviceInfo->setDeviceIP($this->_order->getRemoteIp());
		$this->_buildHttpHeaders($subPayloadDeviceInfo->getHttpHeaders());
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IShipment $subPayloadShipment
	 * @param  Mage_Customer_Model_Address_Abstract $orderShippingAddress
	 * @return self
	 */
	protected function _buildShipment(
		EbayEnterprise_RiskInsight_Model_IShipment $subPayloadShipment,
		Mage_Customer_Model_Address_Abstract $orderShippingAddress
	)
	{
		$subPayloadShipment->setShipmentId($this->_getShipmentId())
			->setEmail($this->_order->getCustomerEmail())
			->setShippingMethod($this->_order->getShippingMethod());

		$this->_buildPersonName($subPayloadShipment->getPersonName(), $orderShippingAddress)
			->_buildTelephone($subPayloadShipment->getTelephone(), $orderShippingAddress)
			->_buildAddress($subPayloadShipment->getAddress(), $orderShippingAddress);
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Person_IName $subPayloadPersonName
	 * @param  Mage_Customer_Model_Address_Abstract $orderAddress
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
	 * @param  EbayEnterprise_RiskInsight_Model_ITelephone $subPayloadTelephone
	 * @param  Mage_Customer_Model_Address_Abstract $orderAddress
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
	 * @param  EbayEnterprise_RiskInsight_Model_IAddress $subPayloadAddress
	 * @param  Mage_Customer_Model_Address_Abstract $orderAddress
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
			->setMainDivisionCode($orderAddress->getRegion())
			->setCountryCode($orderAddress->getCountryId());
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Line_IItem $subPayloadLineItem
	 * @param  Mage_Core_Model_Abstract $orderItem
	 * @return self
	 */
	protected function _buildLineItem(
		EbayEnterprise_RiskInsight_Model_Line_IItem $subPayloadLineItem,
		Mage_Core_Model_Abstract $orderItem
	)
	{
		$subPayloadLineItem->setLineItemId($orderItem->getId())
			->setShipmentId($this->_getShipmentId())
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
	 * @param  EbayEnterprise_RiskInsight_Model_Line_IItem $subPayloadPayment
	 * @param  Mage_Customer_Model_Address_Abstract $orderBillingAddress
	 * @param  Mage_Sales_Model_Order_Payment $orderPayment
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

		$this->_buildPaymentCard($subPayloadPayment->getPaymentCard(), $orderBillingAddress, $orderPayment)
			->_buildPersonName($subPayloadPayment->getPersonName(), $orderBillingAddress)
			->_buildTelephone($subPayloadPayment->getTelephone(), $orderBillingAddress)
			->_buildAddress($subPayloadPayment->getAddress(), $orderBillingAddress)
			->_buildTransactionResponses($subPayloadPayment->getTransactionResponses(), $orderPayment);
		return $this;
	}

	/**
	 * @param  Mage_Customer_Model_Address_Abstract $orderAddress
	 * @return string
	 */
	protected function _getCardHolderName(Mage_Customer_Model_Address_Abstract $orderAddress)
	{
		$name = $orderAddress->getFirstname();
		$middle = $orderAddress->getMiddlename();
		$name .= $middle ? ' ' . $middle . ' ' : ' ';
		return $name . $orderAddress->getLastname();
	}

	/**
	 * @param  Mage_Sales_Model_Order_Payment $orderPayment
	 * @return string | null
	 */
	protected function _getExpireDate(Mage_Sales_Model_Order_Payment $orderPayment)
	{
		$month = $orderPayment->getCcExpMonth();
		$month = strlen($month) > 1 ? $month : '0' . $month;
		$year = $orderPayment->getCcExpYear();
		return ($year > 0 && $month > 0) ? $year . '-' . $month : null;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Payment_ICard $subPayloadCard
	 * @param  Mage_Customer_Model_Address_Abstract $orderBillingAddress
	 * @param  Mage_Sales_Model_Order_Payment $orderPayment
	 * @return self
	 */
	protected function _buildPaymentCard(
		EbayEnterprise_RiskInsight_Model_Payment_ICard $subPayloadCard,
		Mage_Customer_Model_Address_Abstract $orderBillingAddress,
		Mage_Sales_Model_Order_Payment $orderPayment
	)
	{
		$subPayloadCard->setCardHolderName($this->_getCardHolderName($orderBillingAddress))
			->setPaymentAccountUniqueId(null)
			->setIsToken(static::IS_TOKEN)
			->setPaymentAccountBin($this->_helper->getAccountBin($orderPayment))
			->setExpireDate($this->_getExpireDate($orderPayment))
			->setCardType($this->_helper->getPaymentMethod($this->_order, $orderPayment));
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Transaction_IResponses $subPayloadResponses
	 * @param  Mage_Sales_Model_Order_Payment $orderPayment
	 * @return self
	 */
	protected function _buildTransactionResponses(
		EbayEnterprise_RiskInsight_Model_Transaction_IResponses $subPayloadResponses,
		Mage_Sales_Model_Order_Payment $orderPayment
	)
	{
		$subPayloadResponse = $subPayloadResponses->getEmptyTransactionResponse();
		$this->_buildTransactionResponse($subPayloadResponse, $orderPayment);
		$subPayloadResponses->offsetSet($subPayloadResponse);
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Transaction_IResponse $subPayloadResponse
	 * @param  Mage_Sales_Model_Order_Payment $orderPayment
	 * @return self
	 */
	protected function _buildTransactionResponse(
		EbayEnterprise_RiskInsight_Model_Transaction_IResponse $subPayloadResponse,
		Mage_Sales_Model_Order_Payment $orderPayment
	)
	{
		$subPayloadResponse->setResponse($orderPayment->getCcAvsStatus())
			->setResponseType(static::RESPONSE_TYPE);
		return $this;
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Http_IHeaders $subPayloadHttpHeaders
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
	 * @param  EbayEnterprise_RiskInsight_Model_Http_IHeader $subPayloadHttpHeader
	 * @param  string $name
	 * @param  string $message
	 * @return self
	 */
	protected function _buildHttpHeader(EbayEnterprise_RiskInsight_Model_Http_IHeader $subPayloadHttpHeader, $name, $message)
	{
		$subPayloadHttpHeader->setHeader($message)
			->setName($name);
		return $this;
	}
}
