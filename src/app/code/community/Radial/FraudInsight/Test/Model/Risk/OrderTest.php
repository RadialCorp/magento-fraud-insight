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

class Radial_FraudInsight_Test_Model_Risk_OrderTest
	extends EcomDev_PHPUnit_Test_Case
{
	/** @var Radial_FraudInsight_Model_Risk_Order */
	protected $_order;

	public function setUp()
	{
		parent::setUp();

		$this->_order = $this->getModelMockBuilder('radial_fraudinsight/risk_order')
			->setMethods(array('_getApi'))
			->setConstructorArgs(array(array(
				'helper' => $this->_mockHelper(),
			)))
			->getMock();
	}

	/**
	 * Instantiate new object from the passed in class.
	 *
	 * @param  string
	 * @param  mixed
	 * @return mixed
	 */
	protected function _getNewSdkInstance($class, $argments=array())
	{
		return new $class($argments);
	}

	/**
	 * @return Mock_Radial_FraudInsight_Helper_Data
	 */
	protected function _mockHelper()
	{
		$helper = $this->getHelperMock('radial_fraudinsight/data', array(
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
	 * @return Radial_FraudInsight_Model_Resource_Risk_Insight_Collection
	 */
	protected function _buildRiskInsightCollection()
	{
		$collection = Mage::getResourceModel('radial_fraudinsight/risk_insight_collection');
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
	 * @return Radial_FraudInsight_Sdk_Request
	 */
	protected function _buildRequest()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Request');
	}

	/**
	 * @return Radial_FraudInsight_Sdk_Response
	 */
	protected function _buildResponse()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Response');
	}

	/**
	 * @return Radial_FraudInsight_Sdk_Config
	 */
	protected function _buildApiConfig()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Config', array(
			'api_key' => '0393837376716267jsjj322323k',
			'host' => 'https://localhost/risk/fraud/service',
			'store_id' => 'MGS1332',
			'request' => $this->_buildRequest(),
			'response' => $this->_buildResponse(),
		));
	}

	/**
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _buildInsight()
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
	 * @return Mock_Radial_FraudInsight_Sdk_Api
	 */
	protected function _mockApi()
	{
		$api = $this->getMockBuilder('Radial_FraudInsight_Sdk_Api')
			->setMethods(array('send', 'getResponseBody'))
			->setConstructorArgs(array($this->_buildApiConfig()))
			->getMock();
		$api->expects($this->any())
			->method('send')
			->will($this->returnSelf());
		$api->expects($this->any())
			->method('getResponseBody')
			->will($this->returnValue($this->_buildRequest()));
		return $api;
	}

	/**
	 * @return Mock_Radial_FraudInsight_Sdk_Api
	 */
	protected function _mockApiThrownException()
	{
		$api = $this->getMockBuilder('Radial_FraudInsight_Sdk_Api')
			->setMethods(array('send', 'getResponseBody'))
			->setConstructorArgs(array($this->_buildApiConfig()))
			->getMock();
		$api->expects($this->any())
			->method('send')
			->will($this->throwException(new Exception('Simulating API Failure')));
		return $api;
	}

	/**
	 * @return self
	 */
	protected function _mockBuildRequest()
	{
		$request = $this->getModelMockBuilder('radial_fraudinsight/build_request')
			->setMethods(array('build'))
			->setConstructorArgs(array(array('order' => $this->_buildOrder())))
			->getMock();
		$request->expects($this->any())
			->method('build')
			->will($this->returnValue($this->_buildRequest()));
		$this->replaceByMock('model', 'radial_fraudinsight/build_request', $request);
		return $this;
	}

	/**
	 * @return self
	 */
	protected function _mockProcessResponse()
	{
		$response = $this->getModelMockBuilder('radial_fraudinsight/process_response')
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
		$this->replaceByMock('model', 'radial_fraudinsight/process_response', $response);
		return $this;
	}

	/**
	 * Test that when the method radial_fraudinsight/risk_order::process
	 * is invoke, it will get a collection of risk insight record that's flag
	 * as request not be sent yet. Then, continue to loop through each record.
	 * Fetch a sales/order object build risk insight request, send request to risk API
	 * get a response and then process the response accordingly.
	 */
	public function testOrderProcess()
	{
		$this->_order->expects($this->any())
			->method('_getApi')
			->will($this->returnValue($this->_mockApi()));

		$this->_mockBuildRequest()
			->_mockProcessResponse();
		$this->assertSame($this->_order, $this->_order->process());
	}

	/**
	 * Test the API will throw an exception on sending the request.
	 */
	public function testOrderProcessWithApiException()
	{
		$this->_order->expects($this->any())
			->method('_getApi')
			->will($this->returnValue($this->_mockApiThrownException()));

		$this->_mockBuildRequest()
			->_mockProcessResponse();
		$this->assertSame($this->_order, $this->_order->process());
	}
}
