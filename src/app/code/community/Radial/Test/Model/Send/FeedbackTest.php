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

class Radial_FraudInsight_Test_Model_Send_FeedbackTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Instantiate new SDK class.
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
	 * @return Radial_FraudInsight_Model_IFeedback
	 */
	protected function _getEmptyFeedbackRequest()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback');
	}

	/**
	 * @return Radial_FraudInsight_Model_Feedback_IResponse
	 */
	protected function _getEmptyFeedbackResponse()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback_Response');
	}

	/**
	 * @return Radial_FraudInsight_Sdk_IConfig
	 */
	protected function _getEmptyApiConfig()
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Config');
	}

	/**
	 * @return Radial_FraudInsight_Sdk_IApi
	 */
	protected function _getEmptyApi(Radial_FraudInsight_Sdk_IConfig $config)
	{
		return $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Api', $config);
	}

	/**
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _buildInsight()
	{
		return Mage::getModel('radial_fraudinsight/risk_insight', array(
			'id' => 6,
			'order_increment_id' => '10000000011',
			'is_feedback_request_sent' => 0,
			'feedback_sent_attempt_count' => 0,
			'action_taken_acknowledgement' => 0,
			'charge_back_acknowledgement' => 0,
		));
	}

	/**
	 * @return Mage_Sales_Model_Order
	 */
	protected function _buildOrder()
	{
		return Mage::getModel('sales/order', array(
			'increment_id' => '10000000011',
			'state' => Mage_Sales_Model_Order::STATE_CANCELED,
			'status' => 'canceled',
		));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::send()
	 * is invoked, it will call the radial_fraudinsight/send_feedback::_sendFeedback() method.
	 * This method will return an instance of type Radial_FraudInsight_Sdk_IPayload.
	 * the radial_fraudinsight/send_feedback::send() will return this
	 * Radial_FraudInsight_Sdk_IPayload object.
	 */
	public function testSendFeedbackSendMethod()
	{
		$feedbackRequest = $this->_getEmptyFeedbackRequest();
		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array('_sendFeedback'))
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_sendFeedback')
			->will($this->returnValue($feedbackRequest));

		$this->assertSame($feedbackRequest, $sendFeedback->send());
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_sendFeedback()
	 * is invoked, it will call the radial_fraudinsight/send_feedback::_buildFeedbackRequestFromOrder()
	 * method and passed as first parameter the sales/order object instance and then pass as second parameter the
	 * return value from calling the method radial_fraudinsight/send_feedback::_getNewEmptyFeedbackRequest().
	 * Then, the method radial_fraudinsight/send_feedback::_setupApiConfig() will be invoked. It will be passed
	 * as first parameter the return value from calling the method radial_fraudinsight/send_feedback::_sendFeedback()
	 * and as second parameter return value from calling the method radial_fraudinsight/send_feedback::_getEmptyFeedbackResponse().
	 * Then, the method radial_fraudinsight/send_feedback::_sendFeedbackRequest() will be called and passed as parameter
	 * the return value from calling the method radial_fraudinsight/send_feedback::_getApi() passing in the return value
	 * from calling the method radial_fraudinsight/send_feedback::_setupApiConfig().
	 * Then, if the return value return from calling radial_fraudinsight/send_feedback::_sendFeedbackRequest() method is
	 * a valid object of type Radial_FraudInsight_Sdk_IPayload only then will the method
	 * radial_fraudinsight/send_feedback::_processFeedbackResponse() be invoked passing as first parameter the return value
	 * from calling radial_fraudinsight/send_feedback::_sendFeedbackRequest() and passing as second parameter the
	 * radial_fraudinsight/risk_insight object. The radial_fraudinsight/send_feedback::_sendFeedback() method
	 * will return an object of type Radial_FraudInsight_Sdk_IPayload.
	 */
	public function testSendFeedback()
	{
		$feedbackRequest = $this->_getEmptyFeedbackRequest();
		$feedbackResponse = $this->_getEmptyFeedbackResponse();
		$riskInsight = $this->_buildInsight();
		$order = $this->_buildOrder();
		$apiConfig = $this->_getEmptyApiConfig();
		$api = $this->_getEmptyApi($apiConfig);

		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array(
				'_buildFeedbackRequestFromOrder', '_getNewEmptyFeedbackRequest', '_getNewEmptyFeedbackResponse'
				, '_setupApiConfig', '_getApi', '_sendFeedbackRequest', '_processFeedbackResponse'
			))
			// key 'order' and 'insight' are required when instantiating the
			// radial_fraudinsight/send_feedback class.
			->setConstructorArgs(array(array(
				'order' => $order,
				'insight' => $riskInsight,
			)))
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_buildFeedbackRequestFromOrder')
			->with($this->identicalTo($order), $this->identicalTo($feedbackRequest))
			->will($this->returnValue($feedbackRequest));
		$sendFeedback->expects($this->once())
			->method('_getNewEmptyFeedbackRequest')
			->will($this->returnValue($feedbackRequest));
		$sendFeedback->expects($this->once())
			->method('_getNewEmptyFeedbackResponse')
			->will($this->returnValue($feedbackResponse));
		$sendFeedback->expects($this->once())
			->method('_setupApiConfig')
			->with($this->identicalTo($feedbackRequest), $this->identicalTo($feedbackResponse))
			->will($this->returnValue($apiConfig));
		$sendFeedback->expects($this->once())
			->method('_getApi')
			->with($this->identicalTo($apiConfig))
			->will($this->returnValue($api));
		$sendFeedback->expects($this->once())
			->method('_sendFeedbackRequest')
			->with($this->identicalTo($api))
			->will($this->returnValue($feedbackResponse));
		$sendFeedback->expects($this->once())
			->method('_processFeedbackResponse')
			->with($this->identicalTo($feedbackResponse), $this->identicalTo($riskInsight))
			->will($this->returnSelf());

		$this->assertSame($feedbackResponse, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_sendFeedback', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_getNewEmptyFeedbackRequest()
	 * is invoked, an instance of type Radial_FraudInsight_Sdk_Feedback will be created and return.
	 */
	public function testGetNewEmptyFeedbackRequest()
	{
		$feedbackRequest = $this->getMock('Radial_FraudInsight_Sdk_Feedback');
		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array('_getNewSdkInstance'))
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('Radial_FraudInsight_Sdk_Feedback'), $this->identicalTo(array()))
			->will($this->returnValue($feedbackRequest));

		$this->assertSame($feedbackRequest, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_getNewEmptyFeedbackRequest', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_getNewEmptyFeedbackResponse()
	 * is invoked, an instance of type Radial_FraudInsight_Sdk_Feedback_Response will be created and return.
	 */
	public function testGetNewEmptyFeedbackResponse()
	{
		$feedbackResponse = $this->getMock('Radial_FraudInsight_Sdk_Feedback_Response');
		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array('_getNewSdkInstance'))
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('Radial_FraudInsight_Sdk_Feedback_Response'), $this->identicalTo(array()))
			->will($this->returnValue($feedbackResponse));

		$this->assertSame($feedbackResponse, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_getNewEmptyFeedbackResponse', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_setupApiConfig()
	 * is invoked, an instance of type radial_fraudinsight/config will be instantiated and also
	 * expect the following methods to be called from the helper class: radial_fraudinsight/config::getApiKey(),
	 * radial_fraudinsight/config::getApiHostname(), and radial_fraudinsight/config::getStoreId()
	 * which their values will be passed to as an array of key/value pair to the constructor of
	 * radial_fraudinsight/config model class.
	 */
	public function testSetupApiConfig()
	{
		$apiKey = 'alskk3k3l2;23;3;;4;34k43k4jh';
		$hostname = 'http://example.api.com/risk/inisght/feedback/request.xml';
		$storeId = 'TST';
		$riskInsight = $this->_buildInsight();
		$order = $this->_buildOrder();

		$feedbackRequest = $this->getMock('Radial_FraudInsight_Sdk_Feedback');
		$feedbackResponse = $this->getMock('Radial_FraudInsight_Sdk_Feedback_Response');

		$helperConfig = $this->getHelperMock('radial_fraudinsight/config', array('getApiKey', 'getApiHostname', 'getStoreId'));
		$helperConfig->expects($this->once())
			->method('getApiKey')
			->will($this->returnValue($apiKey));
		$helperConfig->expects($this->once())
			->method('getApiHostname')
			->will($this->returnValue($hostname));
		$helperConfig->expects($this->once())
			->method('getStoreId')
			->will($this->returnValue($storeId));

		$apiConfig = $this->getMock('Radial_FraudInsight_Sdk_Config', array(), array(array(
			// Expecting an array with these key/value pairs to be passed to the constructor method
			'api_key' => $apiKey,
			'host' => $hostname,
			'store_id' => $storeId,
			'request' => $feedbackRequest,
			'response' => $feedbackResponse,
		)));

		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			// key 'order' and 'insight' are required when instantiating the
			// radial_fraudinsight/send_feedback class.
			->setConstructorArgs(array(array(
				'order' => $order,
				'insight' => $riskInsight,
				'config' => $helperConfig,
			)))
			->setMethods(array('_getNewSdkInstance'))
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('Radial_FraudInsight_Sdk_Config'), $this->identicalTo(array(
				'api_key' => $apiKey,
				'host' => $hostname,
				'store_id' => $storeId,
				'request' => $feedbackRequest,
				'response' => $feedbackResponse,
			)))
			->will($this->returnValue($apiConfig));

		$this->assertSame($apiConfig, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$sendFeedback, '_setupApiConfig', array($feedbackRequest, $feedbackResponse
		)));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_getApi()
	 * is invoked, an instance of type Radial_FraudInsight_Sdk_Api will be created and
	 * an object of type Radial_FraudInsight_Sdk_IConfig will be passed parameter
	 * to the constructor method of this class. The method radial_fraudinsight/send_feedback::_getApi()
	 * will return the instance of type Radial_FraudInsight_Sdk_IApi.
	 */
	public function testGetSendFeedbackApi()
	{
		$apiConfig = $this->_getEmptyApiConfig();
		// The Radial_FraudInsight_Sdk_Api class constructor requires an instance
		// of type Radial_FraudInsight_Sdk_IConfig parameter to be passed.
		$api = $this->getMock('Radial_FraudInsight_Sdk_Api', array(), array($apiConfig));
		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array('_getNewSdkInstance'))
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('Radial_FraudInsight_Sdk_Api'), $this->identicalTo($apiConfig))
			->will($this->returnValue($api));

		$this->assertSame($api, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_getApi', array($apiConfig)));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_sendFeedbackRequest()
	 * is invoked, it will try to call Radial_FraudInsight_Sdk_Api::send() method if no exception
	 * is thrown it will continue to call the Radial_FraudInsight_Sdk_Api::getResponseBody() method.
	 * The method Radial_FraudInsight_Sdk_Api::getResponseBody() will return an instance of
	 * Radial_FraudInsight_Sdk_Feedback_Response class. This instance will be use as the return
	 * object for the method radial_fraudinsight/send_feedback::_sendFeedbackRequest().
	 */
	public function testSendFeedbackRequest()
	{
		$apiConfig = $this->_getEmptyApiConfig();
		$feedbackResponse = $this->_getEmptyFeedbackResponse();
		$api = $this->getMock('Radial_FraudInsight_Sdk_Api', array('send', 'getResponseBody'), array($apiConfig));
		$api->expects($this->once())
			->method('send')
			->will($this->returnSelf());
		$api->expects($this->once())
			->method('getResponseBody')
			->will($this->returnValue($feedbackResponse));

		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array())
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();

		$this->assertSame($feedbackResponse, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_sendFeedbackRequest', array($api)));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_sendFeedbackRequest()
	 * is invoked, it will try to call Radial_FraudInsight_Sdk_Api::send() method and an exception
	 * will be thrown therefore the method Radial_FraudInsight_Sdk_Api::getResponseBody() will never be called
	 * Catch block will log the exception and the return value for the method
	 * radial_fraudinsight/send_feedback::_sendFeedbackRequest() will null.
	 */
	public function testSendFeedbackRequestApiSendThrowException()
	{
		$apiConfig = $this->_getEmptyApiConfig();
		$feedbackResponse = null;
		$api = $this->getMock('Radial_FraudInsight_Sdk_Api', array('send', 'getResponseBody'), array($apiConfig));
		$api->expects($this->once())
			->method('send')
			->will($this->throwException(new Exception('Simulating Feedback API Failure')));
		// This mean an exception was thrown, this method was never reached.
		$api->expects($this->never())
			->method('getResponseBody');

		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array())
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();

		$this->assertSame($feedbackResponse, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_sendFeedbackRequest', array($api)));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_buildFeedbackRequestFromOrder()
	 * is invoked, it will be passed as first parameter an instance of sales/order object and as second
	 * parameter an instance of Radial_FraudInsight_Sdk_Feedback object. Then, it will instantiates an object
	 * of type radial_fraudinsight/build_feedback passing to its constructor method an array with key
	 * 'order' mapped to a sales/order object and another key 'feedback' mapped to an
	 * Radial_FraudInsight_Sdk_Feedback instance. Then, the method radial_fraudinsight/build_feedback::build()
	 * will be invoked on the radial_fraudinsight/build_feedback class. The return value from calling the method
	 * radial_fraudinsight/build_feedback::build() will be Radial_FraudInsight_Sdk_Feedback instance.
	 * Finally, the method radial_fraudinsight/send_feedback::_buildFeedbackRequestFromOrder() will return
	 * this Radial_FraudInsight_Sdk_Feedback instance.
	 */
	public function testBuildFeedbackRequestFromOrder()
	{
		$order = $this->_buildOrder();
		$feedbackRequest = $this->getMock('Radial_FraudInsight_Sdk_Feedback');
		$buildFeedback = $this->getModelMock('radial_fraudinsight/build_feedback', array('build'), false, array(array(
			'order' => $order,
			'feedback' => $feedbackRequest,
		)));
		$buildFeedback->expects($this->once())
			->method('build')
			->will($this->returnValue($feedbackRequest));
		$this->replaceByMock('model', 'radial_fraudinsight/build_feedback', $buildFeedback);

		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array())
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();

		$this->assertSame($feedbackRequest, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$sendFeedback, '_buildFeedbackRequestFromOrder', array($order, $feedbackRequest)
		));
	}

	/**
	 * Test that when the method radial_fraudinsight/send_feedback::_processFeedbackResponse()
	 * is invoked, it will be passed as first parameter an instance of Radial_FraudInsight_Sdk_Feedback_Response
	 * object and as second parameter an instance of radial_fraudinsight/risk_insight object.
	 * Then, it will instantiates an object of type radial_fraudinsight/process_feedback_response passing to its
	 * constructor method an array with key 'response' mapped to an Radial_FraudInsight_Sdk_Feedback_Response object
	 * and another key 'insight' mapped to an radial_fraudinsight/risk_insight instance. Then, the method
	 * radial_fraudinsight/process_feedback_response::process() will be invoked on the
	 * radial_fraudinsight/process_feedback_response class. Finally, the method
	 * radial_fraudinsight/send_feedback::_processFeedbackResponse() will return itself.
	 */
	public function testProcessFeedbackResponse()
	{
		$riskInsight = $this->_buildInsight();
		$feedbackResponse = $this->_getEmptyFeedbackResponse();
		$processFeedbackResponse = $this->getModelMock('radial_fraudinsight/process_feedback_response', array('process'), false, array(array(
			'response' => $feedbackResponse,
			'insight' => $riskInsight,
		)));
		$processFeedbackResponse->expects($this->once())
			->method('process')
			->will($this->returnSelf());
		$this->replaceByMock('model', 'radial_fraudinsight/process_feedback_response', $processFeedbackResponse);

		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setMethods(array())
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();

		$this->assertSame($sendFeedback, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$sendFeedback, '_processFeedbackResponse', array($feedbackResponse, $riskInsight)
		));
	}
}
