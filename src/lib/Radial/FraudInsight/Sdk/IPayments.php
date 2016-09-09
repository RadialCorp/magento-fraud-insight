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

interface Radial_FraudInsight_Sdk_IPayments extends Countable, Iterator, ArrayAccess, Radial_FraudInsight_Sdk_IIterable
{
	const PAYMENT_MODEL = 'Radial_FraudInsight_Sdk_Payment';
	const ROOT_NODE = 'FormOfPayments';
	const SUBPAYLOAD_XPATH = 'FormOfPayment';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * get an empty payment
	 *
	 * @return Radial_FraudInsight_Sdk_IPayment
	 */
	public function getEmptyPayment();
}
