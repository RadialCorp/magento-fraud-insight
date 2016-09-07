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

class Radial_FraudInsight_Fraud_Insight_Order_CheckController
	extends Mage_Adminhtml_Controller_Action
{
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helperRiskInsight;
	/** @var Radial_FraudInsight_Model_Risk_Order */
	protected $_riskOrder;

	protected function _construct()
	{
		parent::_construct();
		$this->_helperRiskInsight = Mage::helper('radial_fraudinsight');
		$this->_riskOrder = Mage::getModel('radial_fraudinsight/risk_order');
	}

	/**
	 * @return Mage_Sales_Model_Order
	 */
	protected function _initOrder()
	{
		$id = $this->getRequest()->getParam('order_id');
		$order = Mage::getModel('sales/order')->load($id);
		if (!$order->getId()) {
			$this->_redirect('*/*/');
		}
		return $order;
	}

	public function processAction()
	{
		$order = $this->_initOrder();
		$insight = $this->_helperRiskInsight->getRiskInsight($order);
		try{
			$this->_riskOrder->processRiskOrder($insight, $order);
		} catch (Exception $e) {
			$this->_getSession()->addError($this->__("The following error has occurred: {$e->getMessage()}"));
		}
		$this->_checkRequestSent($order);
		$this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
	}

	/**
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _checkRequestSent(Mage_Sales_Model_Order $order)
	{
		if ($this->_helperRiskInsight->isRiskInsightRequestSent($order)) {
			$this->_getSession()->addSuccess($this->__('You have successfully completed risk insight for this order.'));
		} else {
			$this->_getSession()->addError($this->__('Risk Insight request could not be sent, please check your configuration settings.'));
		}
		return $this;
	}

    /**
     * Check access (in the ACL) for current user.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/radial_fraudinsight');
    }
}
