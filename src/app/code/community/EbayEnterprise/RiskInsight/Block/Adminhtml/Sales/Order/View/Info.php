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

class EbayEnterprise_RiskInsight_Block_Adminhtml_Sales_Order_View_Info
	extends Mage_Adminhtml_Block_Sales_Order_View_Info
{
	/**
	 * @return EbayEnterprise_RiskInsight_Model_Risk_Insight
	 */
	public function getRiskInsight()
	{
		return Mage::helper('ebayenterprise_riskinsight')->getRiskInsight($this->getOrder());
	}

	/**
	 * @return bool
	 */
	public function isRiskInsightEnabled()
	{
		return Mage::helper('ebayenterprise_riskinsight/config')->isEnabled();
	}

	/**
	 * Fix for Magento CE 1.6.2.0 or less
	 *
	 * @return bool
	 */
	public function shouldDisplayCustomerIp()
	{
		return method_exists('Mage_Adminhtml_Block_Sales_Order_View_Info', 'shouldDisplayCustomerIp')
			? parent::shouldDisplayCustomerIp()
			: !$this->getOrder()->getRemoteIp();
	}
}
