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
	 * EbayEnterprise_RiskInsight_Helper_Data::getPayloadAsDoc method.
	 *
	 * @expectedException Exception
	 */
	public function testGetPayloadAsDocInvalidPayloadThrowException()
	{
		$invalidXml = '<root><subnode>Blah blah</subnode>';
		$this->_helper->getPayloadAsDoc($invalidXml);
	}

	public function testGetRiskInsightCollection()
	{
		$riskInsightCollection = $this->getResourceModelMockBuilder('ebayenterprise_riskinsight/risk_insight_collection')
			->disableOriginalConstructor()
			->setMethods(array('addFieldToFilter'))
			->getMock();
		$riskInsightCollection->expects($this->once())
			->method('addFieldToFilter')
			->with($this->identicalTo('is_request_sent'), $this->identicalTo(0))
			->will($this->returnSelf());
		$this->replaceByMock('resource_model', 'ebayenterprise_riskinsight/risk_insight_collection', $riskInsightCollection);

		$this->assertSame($riskInsightCollection, $this->_helper->getRiskInsightCollection());
	}

	public function testGetOrderCollectionByIncrementIds()
	{
		$incrementIds = array('10000001', '10000002', '10000003');
		$orderCollection = $this->getResourceModelMockBuilder('sales/order_collection')
			->disableOriginalConstructor()
			->setMethods(array('addFieldToFilter'))
			->getMock();
		$orderCollection->expects($this->once())
			->method('addFieldToFilter')
			->with($this->identicalTo('increment_id'), $this->identicalTo(array('in' => $incrementIds)))
			->will($this->returnSelf());
		$this->replaceByMock('resource_model', 'sales/order_collection', $orderCollection);

		$this->assertSame($orderCollection, $this->_helper->getOrderCollectionByIncrementIds($incrementIds));
	}

	/**
	 * @param  string
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	protected function _mockRiskInsight($orderIncrementId)
	{
		$insight = $this->getModelMock('ebayenterprise_riskinsight/risk_insight', array('load'));
		$insight->expects($this->once())
			->method('load')
			->with($this->identicalTo($orderIncrementId), $this->identicalTo('order_increment_id'))
			->will($this->returnSelf());
		$this->replaceByMock('model', 'ebayenterprise_riskinsight/risk_insight', $insight);
		return $insight;
	}

	public function testGetRiskInsight()
	{
		$orderIncrementId = '100000001';
		$order = Mage::getModel('sales/order', array('increment_id' => $orderIncrementId));
		$insight = $this->_mockRiskInsight($orderIncrementId);
		$this->assertSame($insight, $this->_helper->getRiskInsight($order));
	}

	public function providerIsRiskInsightRequestSent()
	{
		return array(
			array(1, true),
			array(0, false),
		);
	}

	/**
	 * @dataProvider providerIsRiskInsightRequestSent
	 */
	public function testIsRiskInsightRequestSent($isRequestSent, $expected)
	{
		$orderIncrementId = '100000001';
		$order = Mage::getModel('sales/order', array('increment_id' => $orderIncrementId));
		$insight = $this->_mockRiskInsight($orderIncrementId);
		$insight->setIsRequestSent($isRequestSent);
		$this->assertSame($expected, $this->_helper->isRiskInsightRequestSent($order));
	}

	/**
	 * @return array
	 */
	public function providerGetOrderSourceByArea()
	{
		return array(
			array(Mage::getModel('sales/order', array('remote_ip' => null)), 'DASHBOARD'),
			array(Mage::getModel('sales/order', array('remote_ip' => '127.0.0.1')), 'WEBSTORE'),
		);
	}

	/**
	 * @param Mage_Sales_Model_Order
	 * @param string
	 * @dataProvider providerGetOrderSourceByArea
	 */
	public function testGetOrderSourceByArea(Mage_Sales_Model_Order $order, $result)
	{
		$this->assertSame($result, $this->_helper->getOrderSourceByArea($order));
	}
}
