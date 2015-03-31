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

class EbayEnterprise_RiskInsight_Risk_Insight_Order_CheckController
	extends Mage_Adminhtml_Controller_Action
{
	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helperRiskInsight;
	/** @var EbayEnterprise_RiskInsight_Model_Risk_Order */
	protected $_riskOrder;

	protected function _construct()
	{
		parent::_construct();
		$this->_helperRiskInsight = Mage::helper('ebayenterprise_riskinsight');
		$this->_riskOrder = Mage::getModel('ebayenterprise_riskinsight/risk_order');
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
}
