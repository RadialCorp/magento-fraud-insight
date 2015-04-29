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

class EbayEnterprise_RiskInsight_Test_Model_Send_FeedbackTest
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
	 * @return EbayEnterprise_RiskInsight_Model_IFeedback
	 */
	protected function _getEmptyFeedbackRequest()
	{
		return $this->_getNewSdkInstance('EbayEnterprise_RiskInsight_Sdk_Feedback');
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Feedback_IResponse
	 */
	protected function _getEmptyFeedbackResponse()
	{
		return $this->_getNewSdkInstance('EbayEnterprise_RiskInsight_Sdk_Feedback_Response');
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Sdk_IConfig
	 */
	protected function _getEmptyApiConfig()
	{
		return $this->_getNewSdkInstance('EbayEnterprise_RiskInsight_Sdk_Config');
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Sdk_IApi
	 */
	protected function _getEmptyApi(EbayEnterprise_RiskInsight_Sdk_IConfig $config)
	{
		return $this->_getNewSdkInstance('EbayEnterprise_RiskInsight_Sdk_Api', $config);
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	protected function _buildInsight()
	{
		return Mage::getModel('ebayenterprise_riskinsight/risk_insight', array(
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
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::send()
	 * is invoked, it will call the ebayenterprise_riskinsight/send_feedback::_sendFeedback() method.
	 * This method will return an instance of type EbayEnterprise_RiskInsight_Sdk_IPayload.
	 * the ebayenterprise_riskinsight/send_feedback::send() will return this
	 * EbayEnterprise_RiskInsight_Sdk_IPayload object.
	 */
	public function testSendFeedbackSendMethod()
	{
		$feedbackRequest = $this->_getEmptyFeedbackRequest();
		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
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
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_sendFeedback()
	 * is invoked, it will call the ebayenterprise_riskinsight/send_feedback::_buildFeedbackRequestFromOrder()
	 * method and passed as first parameter the sales/order object instance and then pass as second parameter the
	 * return value from calling the method ebayenterprise_riskinsight/send_feedback::_getNewEmptyFeedbackRequest().
	 * Then, the method ebayenterprise_riskinsight/send_feedback::_setupApiConfig() will be invoked. It will be passed
	 * as first parameter the return value from calling the method ebayenterprise_riskinsight/send_feedback::_sendFeedback()
	 * and as second parameter return value from calling the method ebayenterprise_riskinsight/send_feedback::_getEmptyFeedbackResponse().
	 * Then, the method ebayenterprise_riskinsight/send_feedback::_sendFeedbackRequest() will be called and passed as parameter
	 * the return value from calling the method ebayenterprise_riskinsight/send_feedback::_getApi() passing in the return value
	 * from calling the method ebayenterprise_riskinsight/send_feedback::_setupApiConfig().
	 * Then, if the return value return from calling ebayenterprise_riskinsight/send_feedback::_sendFeedbackRequest() method is
	 * a valid object of type EbayEnterprise_RiskInsight_Sdk_IPayload only then will the method
	 * ebayenterprise_riskinsight/send_feedback::_processFeedbackResponse() be invoked passing as first parameter the return value
	 * from calling ebayenterprise_riskinsight/send_feedback::_sendFeedbackRequest() and passing as second parameter the
	 * ebayenterprise_riskinsight/risk_insight object. The ebayenterprise_riskinsight/send_feedback::_sendFeedback() method
	 * will return an object of type EbayEnterprise_RiskInsight_Sdk_IPayload.
	 */
	public function testSendFeedback()
	{
		$feedbackRequest = $this->_getEmptyFeedbackRequest();
		$feedbackResponse = $this->_getEmptyFeedbackResponse();
		$riskInsight = $this->_buildInsight();
		$order = $this->_buildOrder();
		$apiConfig = $this->_getEmptyApiConfig();
		$api = $this->_getEmptyApi($apiConfig);

		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
			->setMethods(array(
				'_buildFeedbackRequestFromOrder', '_getNewEmptyFeedbackRequest', '_getNewEmptyFeedbackResponse'
				, '_setupApiConfig', '_getApi', '_sendFeedbackRequest', '_processFeedbackResponse'
			))
			// key 'order' and 'insight' are required when instantiating the
			// ebayenterprise_riskinsight/send_feedback class.
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
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_getNewEmptyFeedbackRequest()
	 * is invoked, an instance of type EbayEnterprise_RiskInsight_Sdk_Feedback will be created and return.
	 */
	public function testGetNewEmptyFeedbackRequest()
	{
		$feedbackRequest = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Feedback');
		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
			->setMethods(array('_getNewSdkInstance'))
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('EbayEnterprise_RiskInsight_Sdk_Feedback'), $this->identicalTo(array()))
			->will($this->returnValue($feedbackRequest));

		$this->assertSame($feedbackRequest, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_getNewEmptyFeedbackRequest', array()));
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_getNewEmptyFeedbackResponse()
	 * is invoked, an instance of type EbayEnterprise_RiskInsight_Sdk_Feedback_Response will be created and return.
	 */
	public function testGetNewEmptyFeedbackResponse()
	{
		$feedbackResponse = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Feedback_Response');
		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
			->setMethods(array('_getNewSdkInstance'))
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('EbayEnterprise_RiskInsight_Sdk_Feedback_Response'), $this->identicalTo(array()))
			->will($this->returnValue($feedbackResponse));

		$this->assertSame($feedbackResponse, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_getNewEmptyFeedbackResponse', array()));
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_setupApiConfig()
	 * is invoked, an instance of type ebayenterprise_riskinsight/config will be instantiated and also
	 * expect the following methods to be called from the helper class: ebayenterprise_riskinsight/config::getApiKey(),
	 * ebayenterprise_riskinsight/config::getApiHostname(), and ebayenterprise_riskinsight/config::getStoreId()
	 * which their values will be passed to as an array of key/value pair to the constructor of
	 * ebayenterprise_riskinsight/config model class.
	 */
	public function testSetupApiConfig()
	{
		$apiKey = 'alskk3k3l2;23;3;;4;34k43k4jh';
		$hostname = 'http://example.api.com/risk/inisght/feedback/request.xml';
		$storeId = 'TST';
		$riskInsight = $this->_buildInsight();
		$order = $this->_buildOrder();

		$feedbackRequest = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Feedback');
		$feedbackResponse = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Feedback_Response');

		$helperConfig = $this->getHelperMock('ebayenterprise_riskinsight/config', array('getApiKey', 'getApiHostname', 'getStoreId'));
		$helperConfig->expects($this->once())
			->method('getApiKey')
			->will($this->returnValue($apiKey));
		$helperConfig->expects($this->once())
			->method('getApiHostname')
			->will($this->returnValue($hostname));
		$helperConfig->expects($this->once())
			->method('getStoreId')
			->will($this->returnValue($storeId));

		$apiConfig = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Config', array(), array(array(
			// Expecting an array with these key/value pairs to be passed to the constructor method
			'api_key' => $apiKey,
			'host' => $hostname,
			'store_id' => $storeId,
			'request' => $feedbackRequest,
			'response' => $feedbackResponse,
		)));

		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
			// key 'order' and 'insight' are required when instantiating the
			// ebayenterprise_riskinsight/send_feedback class.
			->setConstructorArgs(array(array(
				'order' => $order,
				'insight' => $riskInsight,
				'config' => $helperConfig,
			)))
			->setMethods(array('_getNewSdkInstance'))
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('EbayEnterprise_RiskInsight_Sdk_Config'), $this->identicalTo(array(
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
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_getApi()
	 * is invoked, an instance of type EbayEnterprise_RiskInsight_Sdk_Api will be created and
	 * an object of type EbayEnterprise_RiskInsight_Sdk_IConfig will be passed parameter
	 * to the constructor method of this class. The method ebayenterprise_riskinsight/send_feedback::_getApi()
	 * will return the instance of type EbayEnterprise_RiskInsight_Sdk_IApi.
	 */
	public function testGetSendFeedbackApi()
	{
		$apiConfig = $this->_getEmptyApiConfig();
		// The EbayEnterprise_RiskInsight_Sdk_Api class constructor requires an instance
		// of type EbayEnterprise_RiskInsight_Sdk_IConfig parameter to be passed.
		$api = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Api', array(), array($apiConfig));
		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
			->setMethods(array('_getNewSdkInstance'))
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();
		$sendFeedback->expects($this->once())
			->method('_getNewSdkInstance')
			->with($this->identicalTo('EbayEnterprise_RiskInsight_Sdk_Api'), $this->identicalTo($apiConfig))
			->will($this->returnValue($api));

		$this->assertSame($api, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_getApi', array($apiConfig)));
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_sendFeedbackRequest()
	 * is invoked, it will try to call EbayEnterprise_RiskInsight_Sdk_Api::send() method if no exception
	 * is thrown it will continue to call the EbayEnterprise_RiskInsight_Sdk_Api::getResponseBody() method.
	 * The method EbayEnterprise_RiskInsight_Sdk_Api::getResponseBody() will return an instance of
	 * EbayEnterprise_RiskInsight_Sdk_Feedback_Response class. This instance will be use as the return
	 * object for the method ebayenterprise_riskinsight/send_feedback::_sendFeedbackRequest().
	 */
	public function testSendFeedbackRequest()
	{
		$apiConfig = $this->_getEmptyApiConfig();
		$feedbackResponse = $this->_getEmptyFeedbackResponse();
		$api = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Api', array('send', 'getResponseBody'), array($apiConfig));
		$api->expects($this->once())
			->method('send')
			->will($this->returnSelf());
		$api->expects($this->once())
			->method('getResponseBody')
			->will($this->returnValue($feedbackResponse));

		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
			->setMethods(array())
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();

		$this->assertSame($feedbackResponse, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_sendFeedbackRequest', array($api)));
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_sendFeedbackRequest()
	 * is invoked, it will try to call EbayEnterprise_RiskInsight_Sdk_Api::send() method and an exception
	 * will be thrown therefore the method EbayEnterprise_RiskInsight_Sdk_Api::getResponseBody() will never be called
	 * Catch block will log the exception and the return value for the method
	 * ebayenterprise_riskinsight/send_feedback::_sendFeedbackRequest() will null.
	 */
	public function testSendFeedbackRequestApiSendThrowException()
	{
		$apiConfig = $this->_getEmptyApiConfig();
		$feedbackResponse = null;
		$api = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Api', array('send', 'getResponseBody'), array($apiConfig));
		$api->expects($this->once())
			->method('send')
			->will($this->throwException(new Exception('Simulating Feedback API Failure')));
		// This mean an exception was thrown, this method was never reached.
		$api->expects($this->never())
			->method('getResponseBody');

		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
			->setMethods(array())
			// Disabling the constructor because array key 'order' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->getMock();

		$this->assertSame($feedbackResponse, EcomDev_Utils_Reflection::invokeRestrictedMethod($sendFeedback, '_sendFeedbackRequest', array($api)));
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_buildFeedbackRequestFromOrder()
	 * is invoked, it will be passed as first parameter an instance of sales/order object and as second
	 * parameter an instance of EbayEnterprise_RiskInsight_Sdk_Feedback object. Then, it will instantiates an object
	 * of type ebayenterprise_riskinsight/build_feedback passing to its constructor method an array with key
	 * 'order' mapped to a sales/order object and another key 'feedback' mapped to an
	 * EbayEnterprise_RiskInsight_Sdk_Feedback instance. Then, the method ebayenterprise_riskinsight/build_feedback::build()
	 * will be invoked on the ebayenterprise_riskinsight/build_feedback class. The return value from calling the method
	 * ebayenterprise_riskinsight/build_feedback::build() will be EbayEnterprise_RiskInsight_Sdk_Feedback instance.
	 * Finally, the method ebayenterprise_riskinsight/send_feedback::_buildFeedbackRequestFromOrder() will return
	 * this EbayEnterprise_RiskInsight_Sdk_Feedback instance.
	 */
	public function testBuildFeedbackRequestFromOrder()
	{
		$order = $this->_buildOrder();
		$feedbackRequest = $this->getMock('EbayEnterprise_RiskInsight_Sdk_Feedback');
		$buildFeedback = $this->getModelMock('ebayenterprise_riskinsight/build_feedback', array('build'), false, array(array(
			'order' => $order,
			'feedback' => $feedbackRequest,
		)));
		$buildFeedback->expects($this->once())
			->method('build')
			->will($this->returnValue($feedbackRequest));
		$this->replaceByMock('model', 'ebayenterprise_riskinsight/build_feedback', $buildFeedback);

		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
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
	 * Test that when the method ebayenterprise_riskinsight/send_feedback::_processFeedbackResponse()
	 * is invoked, it will be passed as first parameter an instance of EbayEnterprise_RiskInsight_Sdk_Feedback_Response
	 * object and as second parameter an instance of ebayenterprise_riskinsight/risk_insight object.
	 * Then, it will instantiates an object of type ebayenterprise_riskinsight/process_feedback_response passing to its
	 * constructor method an array with key 'response' mapped to an EbayEnterprise_RiskInsight_Sdk_Feedback_Response object
	 * and another key 'insight' mapped to an ebayenterprise_riskinsight/risk_insight instance. Then, the method
	 * ebayenterprise_riskinsight/process_feedback_response::process() will be invoked on the
	 * ebayenterprise_riskinsight/process_feedback_response class. Finally, the method
	 * ebayenterprise_riskinsight/send_feedback::_processFeedbackResponse() will return itself.
	 */
	public function testProcessFeedbackResponse()
	{
		$riskInsight = $this->_buildInsight();
		$feedbackResponse = $this->_getEmptyFeedbackResponse();
		$processFeedbackResponse = $this->getModelMock('ebayenterprise_riskinsight/process_feedback_response', array('process'), false, array(array(
			'response' => $feedbackResponse,
			'insight' => $riskInsight,
		)));
		$processFeedbackResponse->expects($this->once())
			->method('process')
			->will($this->returnSelf());
		$this->replaceByMock('model', 'ebayenterprise_riskinsight/process_feedback_response', $processFeedbackResponse);

		$sendFeedback = $this->getModelMockBuilder('ebayenterprise_riskinsight/send_feedback')
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
