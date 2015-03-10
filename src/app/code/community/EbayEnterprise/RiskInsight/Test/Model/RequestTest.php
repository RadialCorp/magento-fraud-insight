<?php
/**
 * Copyright (c) 2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

class EbayEnterprise_RiskInsight_Test_Model_RequestTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Create a new payload and set any data passed in the properties parameter.
	 * Each key in array should be a setter method to call and will be given
	 * the value at that key.
	 *
	 * @param  array $properties
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function buildPayload(array $properties = array())
	{
		$payload = $this->createNewPayload();

		foreach ($properties as $setterMethod => $value) {
			$payload->$setterMethod($value);
		}
		return $payload;
	}

	/**
	 * Create a new order Request payload.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function createNewPayload()
	{
		return Mage::getModel('ebayenterprise_riskinsight/request');
	}

	/**
	 * Return a C14N, whitespace removed, XML string.
	 */
	protected function loadXmlTestString($fixtureFile)
	{
		$dom = new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->load($fixtureFile);
		$string = $dom->C14N();

		return $string;
	}

	/**
	 * Provide paths to fixture files containing valid serializations of
	 * order Request payloads.
	 *
	 * @return array
	 */
	public function provideRequestSerializedDataFile()
	{
		return array(
			array(__DIR__ . '/RequestTest/fixtures/RiskInsightRequest.xml'),
			array(__DIR__ . '/RequestTest/fixtures/RiskInsightRequestMinimalData.xml'),
		);
	}

	/**
	 * Test deserializing data into a payload and then deserializing back
	 * to match the original data.
	 *
	 * @param string path to fixture file
	 * @dataProvider provideRequestSerializedDataFile
	 */
	public function testRequestDeserializeSerialize($serializedDataFile)
	{
		$payload = $this->buildPayload();
		$serializedData = $this->loadXmlTestString($serializedDataFile);
		$payload->deserialize($serializedData);
		$this->assertSame($serializedData, $payload->serialize());
	}

	/**
	 * @return array
	 */
	public function providerRequestInvlidPayload()
	{
		return array(
			array(__DIR__ . '/RequestTest/fixtures/InvalidPayload.xml'),
		);
	}

	/**
	 * @param string path to fixture file
	 * @expectedException EbayEnterprise_RiskInsight_Model_Exception_Invalid_Payload_Exception
	 * @dataProvider providerRequestInvlidPayload
	 */
	public function testRequestInvlidPayload($serializedDataFile)
	{
		$payload = $this->buildPayload();
		$serializedData = $this->loadXmlTestString($serializedDataFile);
		$payload->deserialize($serializedData);
	}
}
