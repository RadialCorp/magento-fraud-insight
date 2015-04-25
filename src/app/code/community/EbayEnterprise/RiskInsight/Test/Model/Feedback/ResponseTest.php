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

class EbayEnterprise_RiskInsight_Test_Model_Feedback_ResponseTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Create a new payload and set any data passed in the properties parameter.
	 * Each key in array should be a setter method to call and will be given
	 * the value at that key.
	 *
	 * @param  array
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _buildPayload(array $properties=array())
	{
		$payload = $this->_createNewPayload();
		foreach ($properties as $setterMethod => $value) {
			$payload->$setterMethod($value);
		}
		return $payload;
	}

	/**
	 * Create a new feedback Response payload.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _createNewPayload()
	{
		return Mage::getModel('ebayenterprise_riskinsight/feedback_response');
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
	public function provideFeedbackResponseSerializedDataFile()
	{
		return array(
			array(__DIR__ . '/ResponseTest/fixtures/RiskInsightFeedbackResponse.xml'),
			array(__DIR__ . '/ResponseTest/fixtures/RiskInsightFeedbackResponseMinimalData.xml'),
		);
	}

	/**
	 * Test deserializing data into a payload and then deserializing back
	 * to match the original data.
	 *
	 * @param string $serializedDataFile - path to fixture file
	 * @dataProvider provideFeedbackResponseSerializedDataFile
	 */
	public function testFeedbackResponseDeserializeSerialize($serializedDataFile)
	{
		$payload = $this->_buildPayload();
		$serializedData = $this->_loadXmlTestString($serializedDataFile);
		$payload->deserialize($serializedData);
		$this->assertXmlStringEqualsXmlString($serializedData, $payload->serialize());
	}
}
