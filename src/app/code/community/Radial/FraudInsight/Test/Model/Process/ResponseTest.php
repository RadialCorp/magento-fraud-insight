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

class Radial_FraudInsight_Test_Model_Process_ResponseTest
	extends EcomDev_PHPUnit_Test_Case
{
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
		return new $class();
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
	public function providerProcessResponse()
	{
		return array(
			array(__DIR__ . '/ResponseTest/fixtures/high.xml'),
			array(__DIR__ . '/ResponseTest/fixtures/medium.xml'),
			array(__DIR__ . '/ResponseTest/fixtures/low.xml'),
			array(__DIR__ . '/ResponseTest/fixtures/unknown.xml'),
			array(__DIR__ . '/ResponseTest/fixtures/error.xml'),
		);
	}

	/**
	 * @param  string
	 * @return Radial_FraudInsight_Sdk_Response | Radial_FraudInsight_Sdk_Error
	 */
	protected function _buildResponse($responseFile)
	{
		$class = (basename($responseFile) !== 'error.xml')
			? 'Radial_FraudInsight_Sdk_Response' : 'Radial_FraudInsight_Sdk_Error';
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
				'http_headers' => '{"Authorization":"","Host":"digi-ucp.com","User-Agent":"Mozilla\/5.0 (X11; Ubuntu; Linux x86_64; rv:36.0) Gecko\/20100101 Firefox\/36.0"}',
				'response_reason_code' => NULL,
				'response_reason_code_description' => NULL,
				'is_request_sent' => 0,
				'remote_ip' => '172.17.42.1',
			)))
			->getMock();
		$insight->expects($this->any())
			->method('save')
			->will($this->returnSelf());
		return $insight;
	}

	/**
	 * @return Mage_Sales_Model_Order
	 */
	protected function _mockOrder()
	{
		$order = $this->getModelMockBuilder('sales/order')
			->setMethods(array('save'))
			->setConstructorArgs(array(array(
				'quote_id' => 1,
				'increment_id' => '10000000011',
				'created_at' => '2015-03-18 15:57:01',
				'base_currency_code' => 'USD',
				'coupon_code' => NULL,
				'customer_email' => 'test@example.com',
				'shipping_method' => 'freeshipping_freeshipping',
			)))
			->getMock();
		$order->expects($this->any())
			->method('save')
			->will($this->returnSelf());
		return $order;
	}

	/**
	 * @return Radial_FraudInsight_Helper_Config
	 */
	protected function _mockConfig()
	{
		$config = $this->getHelperMock('radial_fraudinsight/config', array(
			'getHighResponseAction',
			'getMediumResponseAction',
			'getLowResponseAction',
			'getUnknownResponseAction'
		));
		$config->expects($this->any())
			->method('getHighResponseAction')
			->will($this->returnValue(Radial_FraudInsight_Model_System_Config_Source_Responseaction::CANCEL));
		$config->expects($this->any())
			->method('getMediumResponseAction')
			->will($this->returnValue(Radial_FraudInsight_Model_System_Config_Source_Responseaction::HOLD_FOR_REVIEW));
		$config->expects($this->any())
			->method('getLowResponseAction')
			->will($this->returnValue(Radial_FraudInsight_Model_System_Config_Source_Responseaction::PROCESS));
		$config->expects($this->any())
			->method('getUnknownResponseAction')
			->will($this->returnValue(Radial_FraudInsight_Model_System_Config_Source_Responseaction::HOLD_FOR_REVIEW));
		return $config;
	}

	/**
	 * @param string $responseFile - path to fixture file
	 * @dataProvider providerProcessResponse
	 */
	public function testProcessResponse($responseFile)
	{
		$response = Mage::getModel('radial_fraudinsight/process_response', array(
			'response' => $this->_buildResponse($responseFile),
			'insight' => $this->_mockInsight(),
			'order' => $this->_mockOrder(),
			'config' => $this->_mockConfig(),
		));

		$this->assertSame($response, $response->process());
	}
}
