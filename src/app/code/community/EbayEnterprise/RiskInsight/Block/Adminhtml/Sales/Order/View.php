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

class EbayEnterprise_RiskInsight_Block_Adminhtml_Sales_Order_View
	extends Mage_Adminhtml_Block_Sales_Order_View
{
	const RISK_INSIGHT_URI = '*/risk_insight_order_check/process';

	/**
	 * @see Mage_Adminhtml_Block_Sales_Order_View::__construct()
	 * Overriding this constructor method in order to add the Risk Insight
	 * button.
	 */
	public function __construct()
	{
		parent::__construct();
		if ($this->_canShowRiskInsightButton()) {
			$this->_addButton('order_risk_insight', array(
				'label' => Mage::helper('sales')->__('Risk Insight'),
				'onclick' => "setLocation('{$this->getRiskInsightUrl()}')",
			));
		}
	}

	/**
	 * @return bool
	 */
	protected function _canShowRiskInsightButton()
	{
		$insight = Mage::helper('ebayenterprise_riskinsight')->getRiskInsight($this->getOrder());
		return Mage::helper('ebayenterprise_riskinsight/config')->isEnabled()
			&& ((int) $insight->getId() > 0)
			&& ((int) $insight->getIsRequestSent() !== 1);
	}

	/**
	 * @param string
	 */
	public function getRiskInsightUrl()
	{
		return $this->getUrl(static::RISK_INSIGHT_URI);
	}
}