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

class Radial_FraudInsight_Block_Adminhtml_Sales_Order_View_Info
	extends Mage_Adminhtml_Block_Sales_Order_View_Info
{
	/**
	 * @return Radial_FraudInsight_Model_Risk_Insight
	 */
	public function getRiskInsight()
	{
		return Mage::helper('radial_fraudinsight')->getRiskInsight($this->getOrder());
	}

	/**
	 * @return bool
	 */
	public function isRiskInsightEnabled()
	{
		return Mage::helper('radial_fraudinsight/config')->isEnabled();
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
