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

class Radial_FraudInsight_Test_Model_Build_RequestTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Return a C14N, whitespace removed, XML string.
	 *
	 * @param  string
	 * @return string
	 */
	protected function _loadXmlTestString($fixtureFile)
	{
		$dom = new DOMDocument();
		$dom->load($fixtureFile);
		$dom->encoding = 'utf-8';
		$dom->formatOutput = false;
		$dom->preserveWhiteSpace = false;
		$dom->normalizeDocument();
		return $dom->saveXML();
	}

	/**
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _buildRiskInsight()
	{
		return Mage::getModel('radial_fraudinsight/risk_insight', array(
			'id' => 6,
			'order_increment_id' => '10000000011',
			'http_headers' => '{"Authorization":"","Host":"digi-ucp.com","User-Agent":"Mozilla\/5.0 (X11; Ubuntu; Linux x86_64; rv:36.0) Gecko\/20100101 Firefox\/36.0"}',
			'response_reason_code' => NULL,
			'response_reason_code_description' => NULL,
			'is_request_sent' => 0,
			'remote_ip' => '172.17.42.1',
		));
	}

	/**
	 * @return Mage_Sales_Model_Order
	 */
	protected function _buildOrder()
	{
		$order = Mage::getModel('sales/order', array(
			'quote_id' => 1,
			'increment_id' => '10000000011',
			'created_at' => '2015-03-18 15:57:01',
			'base_currency_code' => 'USD',
			'coupon_code' => NULL,
			'customer_email' => 'test@example.com',
			'shipping_method' => 'freeshipping_freeshipping',
		));

		EcomDev_Utils_Reflection::setRestrictedPropertyValue($order, '_items', $this->_buildItems());
		EcomDev_Utils_Reflection::setRestrictedPropertyValue($order, '_payments', $this->_buildPayments());
		EcomDev_Utils_Reflection::setRestrictedPropertyValue($order, '_addresses', $this->_buildAddresses());

		return $order;
	}

	/**
	 * @return Mage_Sales_Model_Order
	 */
	protected function _buildPaypalExpressOrder()
	{
		$order = Mage::getModel('sales/order', array(
			'quote_id' => 1,
			'increment_id' => '10000000011',
			'created_at' => '2015-03-18 15:57:01',
			'base_currency_code' => 'USD',
			'coupon_code' => NULL,
			'customer_email' => 'test@example.com',
			'shipping_method' => 'freeshipping_freeshipping',
		));

		EcomDev_Utils_Reflection::setRestrictedPropertyValue($order, '_items', $this->_buildItems());
		EcomDev_Utils_Reflection::setRestrictedPropertyValue($order, '_payments', $this->_buildPaypalExpressPayments());
		EcomDev_Utils_Reflection::setRestrictedPropertyValue($order, '_addresses', $this->_buildAddresses());

		return $order;
	}

	/**
	 * @return Mage_Sales_Model_Resource_Order_Item_Collection
	 */
	protected function _buildItems()
	{
		$collection = Mage::getResourceModel('sales/order_item_collection');
		$collection->addItem(Mage::getModel('sales/order_item', array(
			'item_id' => 1,
			'order_id' => 1,
			'sku' => 'ace000',
			'name' => 'Aviator Sunglasses',
			'price' => 100.0000,
			'qty_ordered' => 1.0000,
		)));
		$collection->addItem(Mage::getModel('sales/order_item', array(
			'item_id' => 2,
			'order_id' => 1,
			'sku' => 'hkp38832',
			'name' => '5 Year Warranty',
			'price' => 100.0000,
			'qty_ordered' => 1.0000,
			'is_virtual' => 1,
		)));
		return $collection;
	}

	/**
	 * @return Mage_Sales_Model_Resource_Order_Payment_Collection
	 */
	protected function _buildPayments()
	{
		$collection = Mage::getResourceModel('sales/order_payment_collection');
		$collection->addItem(Mage::getModel('sales/order_payment', array(
			'entity_id' => 1,
			'parent_id' => 1,
			'amount_authorized' => NULL,
			'cc_exp_month' => 9,
			'cc_exp_year' => 2023,
			'cc_avs_status' => NULL,
			'cc_owner' => 'Testy Tester',
			'cc_type' => 'VI',
			'base_shipping_amount' => 15.3200,
			'shipping_amount' => 15.3200,
			'base_amount_ordered' => 396.7400,
			'amount_ordered' => 396.7400,
			'method' => 'ccsave',
			'cc_last4' => '1111',
			'cc_number_enc' => Mage::helper('core')->encrypt('4111111111111111'),
		)));
		return $collection;
	}

	/**
	 * @return Mage_Sales_Model_Resource_Order_Payment_Collection
	 */
	protected function _buildPaypalExpressPayments()
	{
		$collection = Mage::getResourceModel('sales/order_payment_collection');
		$collection->addItem(Mage::getModel('sales/order_payment', array(
			'entity_id' => 1,
			'parent_id' => 1,
			'base_amount_authorized' => 278.7500,
			'base_shipping_amount' => 13.5400,
			'shipping_amount' => 13.5400,
			'amount_authorized' => 278.7500,
			'base_amount_ordered' => 278.7500,
			'amount_ordered' => 278.7500,
			'cc_exp_month' => 0,
			'cc_ss_start_year' => 0,
			'method' => 'paypal_express',
			'cc_ss_start_month' => 0,
			'last_trans_id' => '2G669492PB5597430',
			'cc_exp_year' => 0,
			'additional_information' => 'a:12:{s:39:"paypal_express_checkout_shipping_method";s:0:"";s:15:"paypal_payer_id";s:13:"P9PMKWC782MJ8";s:18:"paypal_payer_email";s:33:"tan_1329493113_per@trueaction.com";s:19:"paypal_payer_status";s:8:"verified";s:21:"paypal_address_status";s:9:"Confirmed";s:21:"paypal_correlation_id";s:13:"754844fb5ad55";s:32:"paypal_express_checkout_payer_id";s:13:"P9PMKWC782MJ8";s:29:"paypal_express_checkout_token";s:20:"EC-7Y7954950L097004F";s:41:"paypal_express_checkout_redirect_required";N;s:29:"paypal_protection_eligibility";s:8:"Eligible";s:21:"paypal_payment_status";s:7:"pending";s:21:"paypal_pending_reason";s:13:"authorization";}',
		)));
		return $collection;
	}

	/**
	 * @return Mage_Sales_Model_Resource_Order_Address_Collection
	 */
	protected function _buildAddresses()
	{
		$collection = Mage::getResourceModel('sales/order_address_collection');
		$collection->addItem($this->_buildShippingAddress())
			->addItem($this->_buildBillingAddress());
		return $collection;
	}

	/**
	 * @return Mock_Mage_Catalog_Model_Product
	 */
	protected function _buildProduct()
	{
		$product = $this->getModelMock('catalog/product', array('load', 'getCategoryCollection'));
		$product->expects($this->any())
			->method('load')
			->will($this->returnSelf());
		$product->expects($this->any())
			->method('getCategoryCollection')
			->will($this->returnValue($this->_buildCategoryCollection()));
		$product->setId(1);
		return $product;
	}

	/**
	 * @return Mage_Catalog_Model_Resource_Category_Collection
	 */
	protected function _buildCategoryCollection()
	{
		$collection = Mage::getResourceModel('catalog/category_collection');
		$collection->addItem(Mage::getModel('catalog/category', array(
			'entity_id' => 19,
			'path' => '1/2',
		)));
		return $collection;
	}

	/**
	 * @return Mage_Sales_Model_Order_Address
	 */
	protected function _buildShippingAddress()
	{
		return Mage::getModel('sales/order_address', $this->_getAddress(1, 'shipping'));
	}

	/**
	 * @return Mage_Sales_Model_Order_Address
	 */
	protected function _buildBillingAddress()
	{
		return Mage::getModel('sales/order_address', $this->_getAddress(2, 'billing'));
	}

	/**
	 * @param  string
	 * @param  string
	 * @return array
	 */
	protected function _getAddress($id, $type)
	{
		return array(
			'id' => $id,
			'address_type' => $type,
			'city' => 'KING OF PRUSSIA',
			'country_id' => 'US',
			'email' => 'test@example.com',
			'entity_id' => $id,
			'firstname' => 'Testy',
			'lastname' => 'Tester',
			'parent_id' => '213',
			'postcode' => '19406-1342',
			'region' => 'Pennsylvania',
			'region_id' => '51',
			'street' => '630 Allendale Rd',
			'telephone' => '555-555-5555',
		);
	}

	/**
	 * @return Mock_Mage_Sales_Model_Quote
	 */
	protected function _buildQuote()
	{
		$quote = $this->getModelMock('sales/quote', array('loadByIdWithoutStore', 'getPayment'));
		$quote->expects($this->any())
			->method('loadByIdWithoutStore')
			->will($this->returnSelf());
		$quote->expects($this->any())
			->method('getPayment')
			->will($this->returnValue($this->_buildPayment()));
		$quote->setId(1);
		return $quote;
	}

	/**
	 * @return Mock_Mage_Sales_Model_Quote_Payment
	 */
	protected function _buildPayment()
	{
		return Mage::getModel('sales/quote_payment', array(
			'created_at' => '2015-03-18 15:57:01',
		));
	}

	/**
	 * @return Radial_FraudInsight_Helper_Config
	 */
	protected function _buildConfig()
	{
		$config = $this->getHelperMock('radial_fraudinsight/config', array('getOrderSource', 'getStoreId'));
		$config->expects($this->any())
			->method('getOrderSource')
			->will($this->returnValue('WEBSTORE'));
		$config->expects($this->any())
			->method('getStoreId')
			->will($this->returnValue('1234'));
		return $config;
	}

	/**
	 * Test that when the method radial_fraudinsight/build_request::build
	 * is invoked it will build the 'radial_fraudinsight/risk' object using
	 * the sales/order object.
	 */
	public function testBuildRequest()
	{
		$expectedPayload = $this->_loadXmlTestString(__DIR__ . '/RequestTest/fixtures/RiskInsightRequest.xml');
		$request = Mage::getModel('radial_fraudinsight/build_request', array(
			'insight' => $this->_buildRiskInsight(),
			'order' => $this->_buildOrder(),
			'product' => $this->_buildProduct(),
			'quote' => $this->_buildQuote(),
			'config' => $this->_buildConfig(),
		));
		$this->assertXmlStringEqualsXmlString($expectedPayload, $request->build()->serialize());
	}

	/**
	 * Test building payload request for order using PayPal Express Payment.
	 */
	public function testBuildRequestForPaypalExpressPayment()
	{
		$expectedPayload = $this->_loadXmlTestString(__DIR__ . '/RequestTest/fixtures/RiskInsightRequestForPayPalExpress.xml');
		$request = Mage::getModel('radial_fraudinsight/build_request', array(
			'insight' => $this->_buildRiskInsight(),
			'order' => $this->_buildPaypalExpressOrder(),
			'product' => $this->_buildProduct(),
			'quote' => $this->_buildQuote(),
			'config' => $this->_buildConfig(),
		));
		$this->assertXmlStringEqualsXmlString($expectedPayload, $request->build()->serialize());
	}
}
