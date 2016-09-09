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
