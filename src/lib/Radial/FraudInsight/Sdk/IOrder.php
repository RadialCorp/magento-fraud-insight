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

interface Radial_FraudInsight_Sdk_IOrder extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'Order';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const SHIPPING_LIST_MODEL ='Radial_FraudInsight_Sdk_Shipping_List';
	const LINE_ITEMS_MODEL ='Radial_FraudInsight_Sdk_Line_Items';
	const PAYMENTS_MODEL ='Radial_FraudInsight_Sdk_Payments';
	const TOTAL_MODEL ='Radial_FraudInsight_Sdk_Total';
	const DEVICE_INFO_MODEL ='Radial_FraudInsight_Sdk_Device_Info';

	/**
	 * Unique identifier of the order in the web site.
	 *
	 * xsd restrictions: 1-40 characters
	 * @return string
	 */
	public function getOrderId();

	/**
	 * @param  string
	 * @return self
	 */
	public function setOrderId($orderId);

	/**
	 * Identifies the system/method that was used to take the order.
	 *
	 * Possible Values: {'WEBSTORE'|'DASHBOARD'|'KIOSK'|'MOBILE'|'OTHER'}
	 * @return string
	 */
	public function getOrderSource();

	/**
	 * @param  string
	 * @return self
	 */
	public function setOrderSource($orderSource);

	/**
	 * The timestamp of the order submitted in UTC.
	 * Sample Data: 2015-05-30T09:00:00 or 2015-05-30T09:30:10.5 or 2015-05-30T09:30:10Z
	 *
	 * @return DateTime
	 */
	public function getOrderDate();

	/**
	 * @param  DateTime
	 * @return self
	 */
	public function setOrderDate(DateTime $orderDate);

	/**
	 * Store code/identifier for each partner. New store codes will require configuration by eBay Enterprise.
	 *
	 * xsd restrictions: >= 1 characters
	 * @return string
	 */
	public function getStoreId();

	/**
	 * @param  string
	 * @return self
	 */
	public function setStoreId($storeId);

	/**
	 * @return Radial_FraudInsight_Sdk_Shipping_IList
	 */
	public function getShippingList();

	/**
	 * @param  Radial_FraudInsight_Sdk_Shipping_IList
	 * @return self
	 */
	public function setShippingList(Radial_FraudInsight_Sdk_Shipping_IList $shippingList);

	/**
	 * @return Radial_FraudInsight_Sdk_Line_IItems
	 */
	public function getLineItems();

	/**
	 * @param  Radial_FraudInsight_Sdk_Line_IItems
	 * @return self
	 */
	public function setLineItems(Radial_FraudInsight_Sdk_Line_IItems $lineItems);

	/**
	 * @return Radial_FraudInsight_Sdk_IPayments
	 */
	public function getFormOfPayments();

	/**
	 * @param  Radial_FraudInsight_Sdk_IPayments
	 * @return self
	 */
	public function setFormOfPayments(Radial_FraudInsight_Sdk_IPayments $payments);

	/**
	 * @return Radial_FraudInsight_Sdk_ITotal
	 */
	public function getTotalCost();

	/**
	 * @param  Radial_FraudInsight_Sdk_ITotal
	 * @return self
	 */
	public function setTotalCost(Radial_FraudInsight_Sdk_ITotal $totalCost);

	/**
	 * @return Radial_FraudInsight_Sdk_Device_IInfo
	 */
	public function getDeviceInfo();

	/**
	 * @param  Radial_FraudInsight_Sdk_Device_IInfo
	 * @return self
	 */
	public function setDeviceInfo(Radial_FraudInsight_Sdk_Device_IInfo $deviceInfo);
}
