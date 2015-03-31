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

interface EbayEnterprise_RiskInsight_Model_IOrder extends EbayEnterprise_RiskInsight_Model_IPayload
{
	const ROOT_NODE = 'Order';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const SHIPPING_LIST_MODEL ='ebayenterprise_riskinsight/shipping_list';
	const LINE_ITEMS_MODEL ='ebayenterprise_riskinsight/line_items';
	const PAYMENTS_MODEL ='ebayenterprise_riskinsight/payments';
	const TOTAL_MODEL ='ebayenterprise_riskinsight/total';
	const DEVICE_INFO_MODEL ='ebayenterprise_riskinsight/device_info';

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
	 * @return EbayEnterprise_RiskInsight_Model_Shipping_IList
	 */
	public function getShippingList();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Shipping_IList
	 * @return self
	 */
	public function setShippingList(EbayEnterprise_RiskInsight_Model_Shipping_IList $shippingList);

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Line_IItems
	 */
	public function getLineItems();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Line_IItems
	 * @return self
	 */
	public function setLineItems(EbayEnterprise_RiskInsight_Model_Line_IItems $lineItems);

	/**
	 * @return EbayEnterprise_RiskInsight_Model_IPayments
	 */
	public function getFormOfPayments();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IPayments
	 * @return self
	 */
	public function setFormOfPayments(EbayEnterprise_RiskInsight_Model_IPayments $payments);

	/**
	 * @return EbayEnterprise_RiskInsight_Model_ITotal
	 */
	public function getTotalCost();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_ITotal
	 * @return self
	 */
	public function setTotalCost(EbayEnterprise_RiskInsight_Model_ITotal $totalCost);

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Device_IInfo
	 */
	public function getDeviceInfo();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Device_IInfo
	 * @return self
	 */
	public function setDeviceInfo(EbayEnterprise_RiskInsight_Model_Device_IInfo $deviceInfo);
}
