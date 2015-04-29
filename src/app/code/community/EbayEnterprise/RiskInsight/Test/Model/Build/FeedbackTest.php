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

class EbayEnterprise_RiskInsight_Test_Model_Build_FeedbackTest
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
	 * @return Mage_Sales_Model_Order
	 */
	protected function _buildOrder()
	{
		return Mage::getModel('sales/order', array(
			'increment_id' => '10000000012',
			'state' => Mage_Sales_Model_Order::STATE_CANCELED,
			'status' => 'canceled',
		));
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Helper_Config
	 */
	protected function _buildConfig()
	{
		$config = $this->getHelperMock('ebayenterprise_riskinsight/config', array('getStoreId'));
		$config->expects($this->any())
			->method('getStoreId')
			->will($this->returnValue('1234'));
		return $config;
	}

	/**
	 * Test that when the method ebayenterprise_riskinsight/build_feedback::build
	 * is invoked it will build the 'EbayEnterprise_RiskInsight_Sdk_Feedback' object using
	 * the sales/order object.
	 */
	public function testBuildFeedback()
	{
		$expectedPayload = $this->_loadXmlTestString(__DIR__ . '/FeedbackTest/fixtures/RiskInsightFeedback.xml');
		$feedback = Mage::getModel('ebayenterprise_riskinsight/build_feedback', array(
			'order' => $this->_buildOrder(),
			'config' => $this->_buildConfig(),
		));
		$this->assertXmlStringEqualsXmlString($expectedPayload, $feedback->build()->serialize());
	}
}
