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

class Radial_FraudInsight_Test_Helper_DataTest
	extends EcomDev_PHPUnit_Test_Case
{
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;

	public function setUp()
	{
		parent::setUp();
		$this->_helper = Mage::helper('radial_fraudinsight');
	}

	public function testGetRiskInsightCollection()
	{
		$riskInsightCollection = $this->getResourceModelMockBuilder('radial_fraudinsight/risk_insight_collection')
			->disableOriginalConstructor()
			->setMethods(array('addFieldToFilter'))
			->getMock();
		$riskInsightCollection->expects($this->once())
			->method('addFieldToFilter')
			->with($this->identicalTo('is_request_sent'), $this->identicalTo(0))
			->will($this->returnSelf());
		$this->replaceByMock('resource_model', 'radial_fraudinsight/risk_insight_collection', $riskInsightCollection);

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
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _mockRiskInsight($orderIncrementId)
	{
		$insight = $this->getModelMock('radial_fraudinsight/risk_insight', array('load'));
		$insight->expects($this->once())
			->method('load')
			->with($this->identicalTo($orderIncrementId), $this->identicalTo('order_increment_id'))
			->will($this->returnSelf());
		$this->replaceByMock('model', 'radial_fraudinsight/risk_insight', $insight);
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

	/**
	 * @return array
	 */
	public function providerCanHandleFeedback()
	{
		return array(
			array(
				Mage::getModel('sales/order', array('state' => Mage_Sales_Model_Order::STATE_CANCELED)),
				Mage::getModel('radial_fraudinsight/risk_insight', array(
					'is_request_sent' => 1,
					'is_feedback_sent' => 0,
					'feedback_sent_attempt_count' => 0,
				)),
				true
			),
			array(
				Mage::getModel('sales/order', array('state' => Mage_Sales_Model_Order::STATE_COMPLETE)),
				Mage::getModel('radial_fraudinsight/risk_insight', array(
					'is_request_sent' => 0,
					'is_feedback_sent' => 1,
					'feedback_sent_attempt_count' => 3,
				)),
				false
			),
		);
	}

	/**
	 * Test helper method Radial_FraudInsight_Helper_Data::canHandleFeedback
	 *
	 * @param Mage_Sales_Model_Order
	 * @param Radial_FraudInsight_Model_Risk_Insight
	 * @param bool
	 * @dataProvider providerCanHandleFeedback
	 */
	public function testCanHandleFeedback(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Model_Risk_Insight $insight,
		$result
	)
	{
		$this->assertSame($result, $this->_helper->canHandleFeedback($order, $insight));
	}

	/**
	 * Test helper method Radial_FraudInsight_Helper_Data::getFeedbackOrderCollection
	 */
	public function testGetFeedbackOrderCollection()
	{
		$threshold = 2;
		$feedbackCollection = $this->getResourceModelMockBuilder('radial_fraudinsight/risk_insight_collection')
			->setMethods(array('addFieldToFilter'))
			->getMock();
		$feedbackCollection->expects($this->exactly(3))
			->method('addFieldToFilter')
			->will($this->returnValueMap(array(
				array('is_request_sent', 1, $feedbackCollection),
				array('is_feedback_sent', 0, $feedbackCollection),
				array('feedback_sent_attempt_count', array('lt' => $threshold), $feedbackCollection),
			)));
		$this->replaceByMock('resource_model', 'radial_fraudinsight/risk_insight_collection', $feedbackCollection);

		$this->assertSame($feedbackCollection, $this->_helper->getFeedbackOrderCollection());
	}
}
