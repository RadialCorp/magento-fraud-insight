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

class Radial_FraudInsight_Block_Adminhtml_Sales_Order_View
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
		$insight = Mage::helper('radial_fraudinsight')->getRiskInsight($this->getOrder());
		return Mage::helper('radial_fraudinsight/config')->isEnabled()
			&& ((int) $insight->getId() > 0)
			&& ((int) $insight->getIsRequestSent() !== 1);
	}

	/**
	 * @return string
	 */
	public function getRiskInsightUrl()
	{
		return $this->getUrl(static::RISK_INSIGHT_URI);
	}
}
