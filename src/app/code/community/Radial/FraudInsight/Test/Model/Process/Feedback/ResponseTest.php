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

class Radial_FraudInsight_Test_Model_Process_Feedback_ResponseTest
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
	 * Create a new payload and set any data passed in the properties parameter.
	 * Each key in array should be a setter method to call and will be given
	 * the value at that key.
	 *
	 * @param  string
	 * @param  array
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _buildPayload($class, array $properties=array())
	{
		$payload = $this->_createNewPayload($class);
		foreach ($properties as $setterMethod => $value) {
			$payload->$setterMethod($value);
		}
		return $payload;
	}

	/**
	 * Create a new order Response payload.
	 *
	 * @param  string
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	protected function _createNewPayload($class)
	{
		return $this->_getNewSdkInstance($class);
	}

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
	 * Provide paths to fixture files containing valid serializations of
	 * order Response payloads.
	 *
	 * @return array
	 */
	public function providerProcessFeedbackResponse()
	{
		return array(
			array(__DIR__ . '/ResponseTest/fixtures/RiskInsightFeedbackResponse.xml'),
			array(__DIR__ . '/ResponseTest/fixtures/error.xml'),
		);
	}

	/**
	 * @param  string
	 * @return Radial_FraudInsight_Model_Response
	 */
	protected function _buildFeedbackResponse($responseFile)
	{
		$class = (basename($responseFile) !== 'error.xml')
			? 'Radial_FraudInsight_Sdk_Feedback_Response' : 'Radial_FraudInsight_Sdk_Error';
		$serializedData = $this->_loadXmlTestString($responseFile);
		$payload = $this->_buildPayload($class);
		$payload->deserialize($serializedData);
		return $payload;
	}

	/**
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _mockInsight()
	{
		$insight = $this->getModelMockBuilder('radial_fraudinsight/risk_insight')
			->setMethods(array('save'))
			->setConstructorArgs(array(array(
				'id' => 6,
				'order_increment_id' => '10000000011',
				'is_feedback_request_sent' => 0,
				'feedback_sent_attempt_count' => 0,
				'action_taken_acknowledgement' => 0,
				'charge_back_acknowledgement' => 0,
			)))
			->getMock();
		$insight->expects($this->any())
			->method('save')
			->will($this->returnSelf());
		return $insight;
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::process
	 * is invoked, and the radial_fraudinsight/process_feedback_response constructor instance
	 * is passed in an array with key 'response' mapped to the an instance of
	 * Radial_FraudInsight_Sdk_Feedback_Response and another key 'insight' mapped to an
	 * instance of Radial_FraudInsight_Model_Risk_Insight. It will, then call the method
	 * radial_fraudinsight/process_feedback_response::_updateFeedback and then return
	 * itself. When the passed in array to the constructor has a key 'response' mapped
	 * to an instance of Radial_FraudInsight_Sdk_Error. We expect the method
	 * radial_fraudinsight/process_feedback_response::_processFeedbackError to be invoked.
	 *
	 * @param string $responseFile - path to fixture file
	 * @dataProvider providerProcessFeedbackResponse
	 */
	public function testProcessFeedbackResponse($responseFile)
	{
		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			->setMethods(array('_updateFeedback', '_processFeedbackError'))
			->setConstructorArgs(array(array(
				'response' => $this->_buildFeedbackResponse($responseFile),
				'insight' => $this->_mockInsight(),
			)))
			->getMock();
		if (basename($responseFile) === 'RiskInsightFeedbackResponse.xml') {
			$response->expects($this->once())
				->method('_updateFeedback')
				->will($this->returnSelf());
			$response->expects($this->never())
				// Testing that when a response of type Radial_FraudInsight_Sdk_Feedback_Response
				// is passed in the radial_fraudinsight/process_feedback_response constructor method
				// this method will never be called.
				->method('_processFeedbackError');
		} else {
			$response->expects($this->never())
				// Testing that when a response of type Radial_FraudInsight_Sdk_Error
				// is passed in the radial_fraudinsight/process_feedback_response constructor method
				// this method will never be called.
				->method('_updateFeedback');
			$response->expects($this->once())
				->method('_processFeedbackError')
				->will($this->returnSelf());
		}

		$this->assertSame($response, $response->process());
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::_checkRiskInsight
	 * is invoked, it will call radial_fraudinsight/risk_insight::load() method when there is a non empty
	 * string value return from calling the method Radial_FraudInsight_Sdk_Feedback_Response::getOrderId()
	 * and when calling the method radial_fraudinsight/process_feedback_response::_isLoaded() returns
	 * the boolean value false. It will passed in the increment id as first parameter and the string 'order_increment_id'
	 * as second parameter. Finally, it will return itself.
	 */
	public function testCheckRiskInsight()
	{
		$incrementId = '10000000011';
		$isLoaded = false;

		$feedbackResponse = $this->getMock('Radial_FraudInsight_Sdk_Feedback_Response', array('getOrderId'));
		$feedbackResponse->expects($this->once())
			->method('getOrderId')
			->will($this->returnValue($incrementId));

		$insight = $this->getModelMockBuilder('radial_fraudinsight/risk_insight')
			->setMethods(array('load'))
			->getMock();
		$insight->expects($this->once())
			->method('load')
			->with($this->identicalTo($incrementId), $this->identicalTo('order_increment_id'))
			->will($this->returnSelf());

		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			->setMethods(array('_isLoaded'))
			// Disabling the constructor because the method radial_fraudinsight/process_feedback_response::_checkRiskInsight
			// is being called there.
			->disableOriginalConstructor()
			->getMock();
		$response->expects($this->once())
			->method('_isLoaded')
			->will($this->returnValue($isLoaded));

		EcomDev_Utils_Reflection::setRestrictedPropertyValues($response, array(
			'_response' => $feedbackResponse,
			'_insight' => $insight,
		));

		$this->assertSame($response, EcomDev_Utils_Reflection::invokeRestrictedMethod($response, '_checkRiskInsight', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::_isLoaded
	 * is invoked, it will call radial_fraudinsight/risk_insight::getId varien magic method
	 * if it return non empty string the method radial_fraudinsight/process_feedback_response::_isLoaded
	 * will return boolean value true.
	 */
	public function testIsLoaded()
	{
		$id = 8;
		$feedbackResponse = $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback_Response');
		$insight = $this->getModelMock('radial_fraudinsight/risk_insight', array('getId'));
		$insight->expects($this->once())
			->method('getId')
			->will($this->returnValue($id));

		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			// Disabling the constructor because the method radial_fraudinsight/process_feedback_response::_checkRiskInsight
			// is being called there, which in term calling the radial_fraudinsight/process_feedback_response::_isLoaded
			// method.
			->disableOriginalConstructor()
			->getMock();
		EcomDev_Utils_Reflection::setRestrictedPropertyValues($response, array(
			'_response' => $feedbackResponse,
			'_insight' => $insight,
		));

		$this->assertTrue(EcomDev_Utils_Reflection::invokeRestrictedMethod($response, '_isLoaded', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::_processFeedbackError
	 * is invoked, it will call radial_fraudinsight/risk_insight::_incrementRequetAttempt method, then
	 * call radial_fraudinsight/risk_insight::_logResponse method and then return itself.
	 */
	public function testProcessFeedbackError()
	{
		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			// Disabling the constructor because array key 'response' and 'insight' are required
			// and they are not necessary for this test.
			->disableOriginalConstructor()
			->setMethods(array('_incrementRequetAttempt', '_logResponse'))
			->getMock();
		$response->expects($this->once())
			->method('_incrementRequetAttempt')
			->will($this->returnSelf());
		$response->expects($this->once())
			->method('_logResponse')
			->will($this->returnSelf());

		$this->assertSame($response, EcomDev_Utils_Reflection::invokeRestrictedMethod($response, '_processFeedbackError', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::_logResponse
	 * is invoked, it will call Radial_FraudInsight_Sdk_Feedback_Response::getErrorCode method, then
	 * call Radial_FraudInsight_Sdk_Feedback_Response::getErrorDescription method and then return itself.
	 */
	public function testLogResponse()
	{
		$errorCode = '500';
		$errorDescription = 'API not accessible.';
		$feedbackResponse = $this->getMock('Radial_FraudInsight_Sdk_Feedback_Response', array(
			'getErrorCode', 'getErrorDescription'
		));
		$feedbackResponse->expects($this->once())
			->method('getErrorCode')
			->will($this->returnValue($errorCode));
		$feedbackResponse->expects($this->once())
			->method('getErrorDescription')
			->will($this->returnValue($errorDescription));

		$insight = Mage::getModel('radial_fraudinsight/risk_insight');

		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			->setConstructorArgs(array(array(
				'response' => $feedbackResponse,
				'insight' => $insight,
			)))
			->getMock();

		$this->assertSame($response, EcomDev_Utils_Reflection::invokeRestrictedMethod($response, '_logResponse', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::_incrementRequetAttempt
	 * is invoked, it will call radial_fraudinsight/process_feedback_response::_isLoaded method, if that method
	 * return boolean value true it will proceed to call the method radial_fraudinsight/process_feedback_response::_incrementFeedbackAttemptCount
	 * which will return a radial_fraudinsight/risk_insight instance and then it will call the method
	 * save on that radial_fraudinsight/risk_insight instance. Finally, it will return itself.
	 */
	public function testIncrementRequetAttempt()
	{
		$isLoaded = true;
		$feedbackResponse = $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback_Response');
		$insight = $this->getModelMock('radial_fraudinsight/risk_insight', array('save'));
		$insight->expects($this->once())
			->method('save')
			->will($this->returnSelf());

		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			->setConstructorArgs(array(array(
				'response' => $feedbackResponse,
				'insight' => $insight,
			)))
			->setMethods(array('_isLoaded', '_incrementFeedbackAttemptCount'))
			->getMock();
		$response->expects($this->once())
			->method('_isLoaded')
			->will($this->returnValue($isLoaded));
		$response->expects($this->once())
			->method('_incrementFeedbackAttemptCount')
			->will($this->returnValue($insight));

		$this->assertSame($response, EcomDev_Utils_Reflection::invokeRestrictedMethod($response, '_incrementRequetAttempt', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::_updateFeedback
	 * is invoked, it will call radial_fraudinsight/process_feedback_response::_isLoaded method, if that method
	 * return the boolean value true it will proceed to call the method radial_fraudinsight/process_feedback_response::_incrementFeedbackAttemptCount
	 * which will return a radial_fraudinsight/risk_insight instance, then the method
	 * radial_fraudinsight/risk_insight::setIsFeedbackSent() is invoked and get passed an integer value 1,
	 * then the method radial_fraudinsight/risk_insight::setActionTakenAcknowledgement() is invoked and get passed the return value
	 * from calling the Radial_FraudInsight_Sdk_Feedback_Response::getActionTakenAcknowledgement method,
	 * then the method radial_fraudinsight/risk_insight::setChargeBackAcknowledgement() is invoked and get passed the return value
	 * from calling the Radial_FraudInsight_Sdk_Feedback_Response::getChargeBackAcknowledgement method.
	 * Finally, the method radial_fraudinsight/process_feedback_response::_updateFeedback return itself.
	 */
	public function testUpdateFeedback()
	{
		$isLoaded = true;
		$actionTakenAcknowledgement = 'true';
		$chargeBackAcknowledgement = 'false';
		$convertActionTakenAcknowledgement = true;
		$convertChargeBackAcknowledgement = false;
		$isFeedbackSent = 1;

		$sdkHelper = $this->getMock('Radial_FraudInsight_Sdk_Helper', array('convertStringToBoolean'));
		$sdkHelper->expects($this->exactly(2))
			->method('convertStringToBoolean')
			->will($this->returnValueMap(array(
				array($actionTakenAcknowledgement, $convertActionTakenAcknowledgement),
				array($chargeBackAcknowledgement, $convertChargeBackAcknowledgement),
			)));

		$feedbackResponse = $this->getMock('Radial_FraudInsight_Sdk_Feedback_Response', array(
			'getActionTakenAcknowledgement', 'getChargeBackAcknowledgement'
		));
		$feedbackResponse->expects($this->once())
			->method('getActionTakenAcknowledgement')
			->will($this->returnValue($actionTakenAcknowledgement));
		$feedbackResponse->expects($this->once())
			->method('getChargeBackAcknowledgement')
			->will($this->returnValue($chargeBackAcknowledgement));

		$insight = $this->getModelMock('radial_fraudinsight/risk_insight', array(
			'save', 'setIsFeedbackSent', 'setActionTakenAcknowledgement', 'setChargeBackAcknowledgement'
		));
		$insight->expects($this->once())
			->method('save')
			->will($this->returnSelf());
		$insight->expects($this->once())
			->method('setIsFeedbackSent')
			->with($this->identicalTo($isFeedbackSent))
			->will($this->returnSelf());
		$insight->expects($this->once())
			->method('setActionTakenAcknowledgement')
			->with($this->identicalTo($convertActionTakenAcknowledgement))
			->will($this->returnSelf());
		$insight->expects($this->once())
			->method('setChargeBackAcknowledgement')
			->with($this->identicalTo($convertChargeBackAcknowledgement))
			->will($this->returnSelf());

		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			->setConstructorArgs(array(array(
				'response' => $feedbackResponse,
				'insight' => $insight,
				'sdk_helper' => $sdkHelper,
			)))
			->setMethods(array('_isLoaded', '_incrementFeedbackAttemptCount'))
			->getMock();
		$response->expects($this->once())
			->method('_isLoaded')
			->will($this->returnValue($isLoaded));
		$response->expects($this->once())
			->method('_incrementFeedbackAttemptCount')
			->will($this->returnValue($insight));

		$this->assertSame($response, EcomDev_Utils_Reflection::invokeRestrictedMethod($response, '_updateFeedback', array()));
	}

	/**
	 * Test that when the method radial_fraudinsight/process_feedback_response::_incrementFeedbackAttemptCount()
	 * is invoked, it will call radial_fraudinsight/risk_insight::setFeedbackSentAttemptCount() method
	 * and then passed it the return value from calling radial_fraudinsight/risk_insight::getFeedbackSentAttemptCount()
	 * plus one. Finally, it will return the radial_fraudinsight/risk_insight instance.
	 */
	public function testIncrementFeedbackAttemptCount()
	{
		$feedbackSentAttemptCount = 1;
		$feedbackResponse = $this->_getNewSdkInstance('Radial_FraudInsight_Sdk_Feedback_Response');
		$insight = $this->getModelMock('radial_fraudinsight/risk_insight', array(
			'setFeedbackSentAttemptCount', 'getFeedbackSentAttemptCount'
		));
		$insight->expects($this->once())
			->method('setFeedbackSentAttemptCount')
			->with($this->identicalTo($feedbackSentAttemptCount + 1))
			->will($this->returnSelf());
		$insight->expects($this->once())
			->method('getFeedbackSentAttemptCount')
			->will($this->returnValue($feedbackSentAttemptCount));

		$response = $this->getModelMockBuilder('radial_fraudinsight/process_feedback_response')
			->setConstructorArgs(array(array(
				'response' => $feedbackResponse,
				'insight' => $insight,
			)))
			->getMock();

		$this->assertSame($insight, EcomDev_Utils_Reflection::invokeRestrictedMethod($response, '_incrementFeedbackAttemptCount', array()));
	}
}
