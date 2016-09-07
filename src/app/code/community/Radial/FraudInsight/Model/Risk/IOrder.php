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

interface Radial_FraudInsight_Model_Risk_IOrder
{
	/**
	 * Get a collection of risk insight, send UCP Service Request, base on the response
	 * either change the order status to what is configured from the response code or
	 * simply log message and do nothing.
	 *
	 * @return self
	 */
	public function process();

	/**
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	public function processRiskOrder(Radial_FraudInsight_Model_Risk_Insight $insight, Mage_Sales_Model_Order $order);
}
