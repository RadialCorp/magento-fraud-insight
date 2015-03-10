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

class EbayEnterprise_RiskInsight_Test_Helper_DataTest
	extends EcomDev_PHPUnit_Test_Case
{
	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helper;

	public function setUp()
	{
		parent::setUp();
		$this->_helper = Mage::helper('ebayenterprise_riskinsight');
	}

	/**
	 * @return array
	 */
	public function providerConvertStringToBoolean()
	{
		return array(
			array(array(), null),
			array(null, null),
			array('true', true),
			array('1', true),
			array('false', false),
			array('0', false),
			array('anything', null),
		);
	}

	/**
	 * @param mixed $input
	 * @dataProvider providerConvertStringToBoolean
	 */
	public function testConvertStringToBoolean($input, $expected)
	{
		$this->assertSame($expected, $this->_helper->convertStringToBoolean($input));
	}

	/**
	 * Test that an exception is thrown when an invalid xml string is passed to the
	 * EbayEnterprise_RiskInsight_Helper_Data::getPayloadAsDoc method.
	 * @expectedException Exception
	 */
	public function testGetPayloadAsDocInvalidPayloadThrowException()
	{
		$invalidXml = '<root><subnode>Blah blah</subnode>';
		$this->_helper->getPayloadAsDoc($invalidXml);
	}
}
