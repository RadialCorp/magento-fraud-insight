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

class Radial_FraudInsight_Test_Model_Build_FeedbackTest
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
	 * @return Radial_FraudInsight_Helper_Config
	 */
	protected function _buildConfig()
	{
		$config = $this->getHelperMock('radial_fraudinsight/config', array('getStoreId'));
		$config->expects($this->any())
			->method('getStoreId')
			->will($this->returnValue('1234'));
		return $config;
	}

	/**
	 * Test that when the method radial_fraudinsight/build_feedback::build
	 * is invoked it will build the 'Radial_FraudInsight_Sdk_Feedback' object using
	 * the sales/order object.
	 */
	public function testBuildFeedback()
	{
		$expectedPayload = $this->_loadXmlTestString(__DIR__ . '/FeedbackTest/fixtures/RiskInsightFeedback.xml');
		$feedback = Mage::getModel('radial_fraudinsight/build_feedback', array(
			'order' => $this->_buildOrder(),
			'config' => $this->_buildConfig(),
		));
		$this->assertXmlStringEqualsXmlString($expectedPayload, $feedback->build()->serialize());
	}
}
