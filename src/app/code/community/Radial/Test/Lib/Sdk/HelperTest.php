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

class Radial_FraudInsight_Test_Lib_Sdk_HelperTest
	extends EcomDev_PHPUnit_Test_Case
{

	/** @var Radial_FraudInsight_Sdk_IHelper */
	protected $_helper;

	public function setUp()
	{
		parent::setUp();
		$this->_helper = $this->_createNewHelper();
	}

	/**
	 * Create a new SDK helper instance.
	 *
	 * @return Radial_FraudInsight_Sdk_IHelper
	 */
	protected function _createNewHelper()
	{
		return new Radial_FraudInsight_Sdk_Helper();
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
	 * @param mixed
	 * @param boo | null
	 * @dataProvider providerConvertStringToBoolean
	 */
	public function testConvertStringToBoolean($input, $expected)
	{
		$this->assertSame($expected, $this->_helper->convertStringToBoolean($input));
	}

	/**
	 * Test that an exception is thrown when an invalid xml string is passed to the
	 * Radial_FraudInsight_Sdk_Helper::getPayloadAsDoc method.
	 *
	 * @expectedException Radial_FraudInsight_Sdk_Exception_Invalid_Xml_Exception
	 */
	public function testGetPayloadAsDocInvalidPayloadThrowException()
	{
		$invalidXml = '<root><subnode>Blah blah</subnode>';
		$this->_helper->getPayloadAsDoc($invalidXml);
	}
}
