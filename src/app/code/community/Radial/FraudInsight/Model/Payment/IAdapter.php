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

interface Radial_FraudInsight_Model_Payment_IAdapter
{
	const DEFAULT_ADAPTER = 'radial_fraudinsight/payment_adapter_default';
	const GIFT_CARD_PAYMENT_METHOD = 'giftcard';

	/**
	 * @return Radial_FraudInsight_Model_Payment_Adapter_IType
	 */
	public function getAdapter();
}
