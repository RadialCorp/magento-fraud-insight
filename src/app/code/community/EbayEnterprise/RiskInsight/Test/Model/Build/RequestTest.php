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

class EbayEnterprise_RiskInsight_Test_Model_Build_RequestTest
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
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	protected function _buildRiskInsight()
	{
		return Mage::getModel('ebayenterprise_riskinsight/risk_insight', array(
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
			'cc_number_enc' => '0:2:e8796447aacd7c59:tHo6xuna2cxMJpcvzhPVQQ==',
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
	 * @return EbayEnterprise_RiskInsight_Helper_Config
	 */
	protected function _buildConfig()
	{
		$config = $this->getHelperMock('ebayenterprise_riskinsight/config', array('getOrderSource', 'getStoreId'));
		$config->expects($this->any())
			->method('getOrderSource')
			->will($this->returnValue('WEBSTORE'));
		$config->expects($this->any())
			->method('getStoreId')
			->will($this->returnValue('1234'));
		return $config;
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/build_request::build
	 * is invoked it will build the 'ebayenterprise_riskinsight/risk' object using
	 * the sales/order object.
	 */
	public function testBuildRequest()
	{
		$expectedPayload = $this->_loadXmlTestString(__DIR__ . '/RequestTest/fixtures/RiskInsightRequest.xml');
		$request = Mage::getModel('ebayenterprise_riskinsight/build_request', array(
			'insight' => $this->_buildRiskInsight(),
			'order' => $this->_buildOrder(),
			'product' => $this->_buildProduct(),
			'quote' => $this->_buildQuote(),
			'config' => $this->_buildConfig(),
		));
		$this->assertXmlStringEqualsXmlString($expectedPayload, $request->build()->serialize());
	}
}
