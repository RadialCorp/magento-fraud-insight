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

/**
 * @codeCoverageIgnore
 */
class EbayEnterprise_RiskInsight_Model_Observer
{
	/** @var EbayEnterprise_RiskInsight_Helper_Data $_helper */
	protected $_helper;
	/** @var EbayEnterprise_RiskInsight_Helper_Config $_config*/
	protected $_config;

	public function __construct()
	{
		$this->_helper = Mage::helper('ebayenterprise_riskinsight');
		$this->_config = Mage::helper('ebayenterprise_riskinsight/config');
	}

	/**
	 * Consume the event 'sales_model_service_quote_submit_after'. Pass the Mage_Sales_Model_Order object
	 * from the event down to the 'ebayenterprise_riskinsight/risk_fraud' instance. Invoke the process
	 * method on the 'ebayenterprise_riskinsight/risk_fraud' instance.
	 *
	 * @param  Varien_Event_Observer $observer
	 * @return self
	 */
	public function handleOrderRiskFraud(Varien_Event_Observer $observer)
	{
		Mage::getModel('ebayenterprise_riskinsight/risk_fraud', array(
			'order' => $observer->getEvent()->getOrder(),
			'helper' => $this->_helper,
		))->process();
		return $this;
	}

	/**
	 * Entry point for the CRONJOB 'ebayenterprise_riskinsight_process_risk_fraud'.
	 *
	 * @return self
	 */
	public function detectFraudulentOrders()
	{
		if ($this->_config->isEnabled()) {
			Mage::getModel('ebayenterprise_riskinsight/risk_order', array(
				'helper' => $this->_helper,
			))->process();
		}
		return $this;
	}
}
