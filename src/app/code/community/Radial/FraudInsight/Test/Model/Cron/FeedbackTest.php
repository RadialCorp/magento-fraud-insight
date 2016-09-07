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

class Radial_FraudInsight_Test_Model_Cron_FeedbackTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * @return Radial_FraudInsight_Model_Resource_Risk_Insight_Collection
	 */
	protected function _buildFeedbackOrderCollection()
	{
		$incrementId = '10000000011';
		$collection = Mage::getResourceModel('radial_fraudinsight/risk_insight_collection');
		$collection->addItem($this->_buildInsight($incrementId));
		return $collection;
	}

	/**
	 * @param  string
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	protected function _buildInsight($incrementId)
	{
		return Mage::getModel('radial_fraudinsight/risk_insight', array(
			'id' => 6,
			'order_increment_id' => $incrementId,
			'is_request_sent' => 1,
			'is_feedback_request_sent' => 0,
			'feedback_sent_attempt_count' => 0,
			'action_taken_acknowledgement' => 0,
			'charge_back_acknowledgement' => 0,
		));
	}

	/**
	 * @param  string
	 * @return Mage_Sales_Model_Order
	 */
	protected function _buildOrder($incrementId)
	{
		return Mage::getModel('sales/order', array(
			'increment_id' => $incrementId,
			'state' => Mage_Sales_Model_Order::STATE_COMPLETE,
		));
	}

	/**
	 * Test that when the method radial_fraudinsight/cron_feedback::process()
	 * is invoked, it will call Radial_FraudInsight_Helper_Data::getFeedbackOrderCollection() method
	 * to get a collection of risk insight object and then passed this collection of risk insight object to
	 * the method radial_fraudinsight/cron_feedback::_processFeebackOrderCollection() method and
	 * the method radial_fraudinsight/cron_feedback::process() will return itself.
	 */
	public function testCronFeedbackOrderProcess()
	{
		$riskInsightCollection = $this->_buildFeedbackOrderCollection();
		$helper = $this->getHelperMock('radial_fraudinsight/data', array('getFeedbackOrderCollection'));
		$helper->expects($this->once())
			->method('getFeedbackOrderCollection')
			->will($this->returnValue($riskInsightCollection));

		$feedback = $this->getModelMockBuilder('radial_fraudinsight/cron_feedback')
			->setMethods(array('_processFeebackOrderCollection'))
			->setConstructorArgs(array(array('helper' => $helper)))
			->getMock();
		$feedback->expects($this->once())
			->method('_processFeebackOrderCollection')
			->with($this->identicalTo($riskInsightCollection))
			->will($this->returnSelf());
		$this->assertSame($feedback, $feedback->process());
	}

	/**
	 * Test that when the method radial_fraudinsight/cron_feedback::_processFeebackOrderCollection()
	 * is invoked, it will be passed a Radial_FraudInsight_Model_Resource_Risk_Insight_Collection object
	 * as its parameter. It will call Radial_FraudInsight_Helper_Data::getOrderCollectionByIncrementIds()
	 * method passing an array of order increment ids to get a collection of order object. Then, it will loop
	 * through the passed in collection of risk insight object and for each iteration it will get the corresponding
	 * order object from the collection of order objects, if a valid order object is retrieved it will call
	 * Radial_FraudInsight_Helper_Data::isOrderInAStateToSendFeedback() passing in the order object.
	 * The Radial_FraudInsight_Helper_Data::isOrderInAStateToSendFeedback() method will return true
	 * if the state is either complete or cancel. Then it will proceed to called the method
	 * radial_fraudinsight/cron_feedback::_processOrderFeedback passing in the risk insight object and
	 * the order object. Finally, it will return itself.
	 */
	public function testProcessFeebackOrderCollection()
	{
		$incrementId = '10000000011';
		$order = $this->_buildOrder($incrementId);
		$orderCollection = $collection = Mage::getResourceModel('sales/order_collection');
		$orderCollection->clear();
		$collection->addItem($order);

		$riskInsight = $this->_buildInsight($incrementId);
		$riskInsightCollection = Mage::getResourceModel('radial_fraudinsight/risk_insight_collection');
		$riskInsightCollection->clear();
		$riskInsightCollection->addItem($riskInsight);

		$helper = $this->getHelperMock('radial_fraudinsight/data', array(
			'getOrderCollectionByIncrementIds', 'isOrderInAStateToSendFeedback'
		));
		$helper->expects($this->once())
			->method('getOrderCollectionByIncrementIds')
			// I could of use array($incrementId), however, this test might fail
			// if there is data in test database for sales/order because the method
			// Varien_Data_Collection::getColumnValues() do a load first before getting
			// the column data.
			->with($this->isType('array'))
			->will($this->returnValue($orderCollection));
		$helper->expects($this->once())
			->method('isOrderInAStateToSendFeedback')
			->with($this->identicalTo($order))
			->will($this->returnValue($orderCollection));

		$feedback = $this->getModelMockBuilder('radial_fraudinsight/cron_feedback')
			->setMethods(array('_processOrderFeedback'))
			->setConstructorArgs(array(array('helper' => $helper)))
			->getMock();
		$feedback->expects($this->once())
			->method('_processOrderFeedback')
			->with($this->identicalTo($riskInsight), $this->identicalTo($order))
			->will($this->returnSelf());
		$this->assertSame($feedback, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$feedback, '_processFeebackOrderCollection', array($riskInsightCollection)
		));
	}

	/**
	 * Test that when the method radial_fraudinsight/cron_feedback::_processOrderFeedback
	 * is invoked, it will be passed a Radial_FraudInsight_Model_Risk_Insight object and
	 * a Mage_Sales_Model_Order object as its parameters. It will instantiated a
	 * radial_fraudinsight/send_feedback object passing in an array with key 'order' mapped
	 * to a sales/order object and another key 'insight' mapped to a radial_fraudinsight/risk_insight
	 * object. Then, it will invoke radial_fraudinsight/send_feedback::send method. Finally,
	 * it will return itself.
	 */
	public function testProcessOrderFeedback()
	{
		$incrementId = '10000000011';
		$order = $this->_buildOrder($incrementId);
		$riskInsight = $this->_buildInsight($incrementId);

		$sendFeedback = $this->getModelMockBuilder('radial_fraudinsight/send_feedback')
			->setConstructorArgs(array(array(
				'order' => $order,
				'insight' => $riskInsight,
			)))
			->setMethods(array('send'))
			->getMock();
		$sendFeedback->expects($this->once())
			->method('send')
			->will($this->returnSelf());
		$this->replaceByMock('model', 'radial_fraudinsight/send_feedback', $sendFeedback);

		$feedback = Mage::getModel('radial_fraudinsight/cron_feedback');

		$this->assertSame($feedback, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$feedback, '_processOrderFeedback', array($riskInsight, $order)
		));
	}
}
