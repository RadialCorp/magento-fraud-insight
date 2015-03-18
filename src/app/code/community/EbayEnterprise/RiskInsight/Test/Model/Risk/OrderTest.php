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

class EbayEnterprise_RiskInsight_Test_Model_Risk_OrderTest
	extends EcomDev_PHPUnit_Test_Case
{
	/** @var EbayEnterprise_RiskInsight_Model_Risk_Order $_order */
	protected $_order;

	public function setUp()
	{
		parent::setUp();

		$this->_order = Mage::getModel('ebayenterprise_riskinsight/risk_order', array(
			'helper' => $this->_mockHelper(),
		));
	}

	/**
	 * @return Mock_EbayEnterprise_RiskInsight_Helper_Data
	 */
	protected function _mockHelper()
	{
		$helper = $this->getHelperMock('ebayenterprise_riskinsight/data', array(
			'getRiskInsightCollection', 'getOrderCollectionByIncrementIds'
		));
		$helper->expects($this->any())
			->method('getRiskInsightCollection')
			->will($this->returnValue($this->_buildRiskInsightCollection()));
		$helper->expects($this->any())
			->method('getOrderCollectionByIncrementIds')
			->will($this->returnValue($this->_buildOrderCollection()));
		return $helper;
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Resource_Risk_Insight_Collection
	 */
	protected function _buildRiskInsightCollection()
	{
		$collection = Mage::getResourceModel('ebayenterprise_riskinsight/risk_insight_collection');
		$collection->addItem($this->_buildInsight());
		return $collection;
	}

	/**
	 * @return Mage_Sales_Model_Resource_Order_Collection
	 */
	protected function _buildOrderCollection()
	{
		$collection = Mage::getResourceModel('sales/order_collection');
		$collection->addItem($this->_buildOrder());
		return $collection;
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Request
	 */
	protected function _buildRequest()
	{
		return Mage::getModel('ebayenterprise_riskinsight/request');
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Response
	 */
	protected function _buildResponse()
	{
		return Mage::getModel('ebayenterprise_riskinsight/response');
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Config
	 */
	protected function _buildApiConfig()
	{
		return Mage::getModel('ebayenterprise_riskinsight/config', array(
			'api_key' => '0393837376716267jsjj322323k',
			'host' => 'https://localhost/risk/fraud/service',
			'store_id' => 'MGS1332',
			'request' => $this->_buildRequest(),
			'response' => $this->_buildResponse(),
		));
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	protected function _buildInsight()
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
		return Mage::getModel('sales/order', array(
			'quote_id' => 1,
			'increment_id' => '10000000011',
			'created_at' => '2015-03-18 15:57:01',
			'base_currency_code' => 'USD',
			'coupon_code' => NULL,
			'customer_email' => 'test@example.com',
			'shipping_method' => 'freeshipping_freeshipping',
		));
	}

	/**
	 * @return self
	 */
	protected function _mockApi()
	{
		$api = $this->getModelMockBuilder('ebayenterprise_riskinsight/api')
			->setMethods(array('send', 'getResponseBody'))
			->setConstructorArgs(array($this->_buildApiConfig()))
			->getMock();
		$api->expects($this->any())
			->method('send')
			->will($this->returnSelf());
		$api->expects($this->any())
			->method('getResponseBody')
			->will($this->returnValue($this->_buildRequest()));
		$this->replaceByMock('model', 'ebayenterprise_riskinsight/api', $api);
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _mockApiThrownException()
	{
		$api = $this->getModelMockBuilder('ebayenterprise_riskinsight/api')
			->setMethods(array('send', 'getResponseBody'))
			->setConstructorArgs(array($this->_buildApiConfig()))
			->getMock();
		$api->expects($this->any())
			->method('send')
			->will($this->throwException(new Exception('Simulating API Failure')));
		$this->replaceByMock('model', 'ebayenterprise_riskinsight/api', $api);
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _mockBuildRequest()
	{
		$request = $this->getModelMockBuilder('ebayenterprise_riskinsight/build_request')
			->setMethods(array('build'))
			->setConstructorArgs(array(array('order' => $this->_buildOrder())))
			->getMock();
		$request->expects($this->any())
			->method('build')
			->will($this->returnValue($this->_buildRequest()));
		$this->replaceByMock('model', 'ebayenterprise_riskinsight/build_request', $request);
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _mockProcessResponse()
	{
		$response = $this->getModelMockBuilder('ebayenterprise_riskinsight/process_response')
			->setMethods(array('process'))
			->setConstructorArgs(array(array(
				'response' => $this->_buildResponse(),
				'insight' => $this->_buildInsight(),
				'order' => $this->_buildOrder(),
			)))
			->getMock();
		$response->expects($this->any())
			->method('process')
			->will($this->returnSelf());
		$this->replaceByMock('model', 'ebayenterprise_riskinsight/process_response', $response);
		return $this;
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/risk_order::process
	 * is invoke, it will get a collection of risk insight record that's flag
	 * as request not be sent yet. Then, continue to loop through each record.
	 * Fetch a sales/order object build risk insight request, send request to risk API
	 * get a response and then process the response accordingly.
	 */
	public function testOrderProcess()
	{
		$this->_mockApi()
			->_mockBuildRequest()
			->_mockProcessResponse();
		$this->assertSame($this->_order, $this->_order->process());
	}

	/**
	 * Test the API will throw an exception on sending the request.
	 */
	public function testOrderProcessWithApiException()
	{
		$this->_mockApiThrownException()
			->_mockBuildRequest()
			->_mockProcessResponse();
		$this->assertSame($this->_order, $this->_order->process());
	}
}
