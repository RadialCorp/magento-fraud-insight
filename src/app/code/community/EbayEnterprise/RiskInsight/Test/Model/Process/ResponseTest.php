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

class EbayEnterprise_RiskInsight_Test_Model_Process_ResponseTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Create a new payload and set any data passed in the properties parameter.
	 * Each key in array should be a setter method to call and will be given
	 * the value at that key.
	 *
	 * @param  string
	 * @param  array
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _buildPayload($type, array $properties=array())
	{
		$payload = $this->_createNewPayload($type);
		foreach ($properties as $setterMethod => $value) {
			$payload->$setterMethod($value);
		}
		return $payload;
	}

	/**
	 * Create a new order Response payload.
	 *
	 * @param  string
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _createNewPayload($type)
	{
		return Mage::getModel('ebayenterprise_riskinsight/' . $type);
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
		$dom->preserveWhiteSpace = false;
		$dom->load($fixtureFile);
		$string = $dom->C14N();
		return $string;
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
	 * @return EbayEnterprise_RiskInsight_Model_Response
	 */
	protected function _buildResponse($responseFile)
	{
		$type = (basename($responseFile) !== 'error.xml') ? 'response' : 'error';
		$serializedData = $this->_loadXmlTestString($responseFile);
		$payload = $this->_buildPayload($type);
		$payload->deserialize($serializedData);
		return $payload;
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	protected function _mockInsight()
	{
		$insight = $this->getModelMockBuilder('ebayenterprise_riskinsight/risk_insight')
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
	 * @return EbayEnterprise_RiskInsight_Helper_Config
	 */
	protected function _mockConfig()
	{
		$config = $this->getHelperMock('ebayenterprise_riskinsight/config', array(
			'getHighResponseAction',
			'getMediumResponseAction',
			'getLowResponseAction',
			'getUnknownResponseAction'
		));
		$config->expects($this->any())
			->method('getHighResponseAction')
			->will($this->returnValue(EbayEnterprise_RiskInsight_Model_System_Config_Source_Responseaction::CANCEL));
		$config->expects($this->any())
			->method('getMediumResponseAction')
			->will($this->returnValue(EbayEnterprise_RiskInsight_Model_System_Config_Source_Responseaction::HOLD_FOR_REVIEW));
		$config->expects($this->any())
			->method('getLowResponseAction')
			->will($this->returnValue(EbayEnterprise_RiskInsight_Model_System_Config_Source_Responseaction::PROCESS));
		$config->expects($this->any())
			->method('getUnknownResponseAction')
			->will($this->returnValue(EbayEnterprise_RiskInsight_Model_System_Config_Source_Responseaction::HOLD_FOR_REVIEW));
		return $config;
	}

	/**
	 * @param string $responseFile - path to fixture file
	 * @dataProvider providerProcessResponse
	 */
	public function testProcessResponse($responseFile)
	{
		$response = Mage::getModel('ebayenterprise_riskinsight/process_response', array(
			'response' => $this->_buildResponse($responseFile),
			'insight' => $this->_mockInsight(),
			'order' => $this->_mockOrder(),
			'config' => $this->_mockConfig(),
		));

		$this->assertSame($response, $response->process());
	}
}
