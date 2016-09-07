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

interface Radial_FraudInsight_Model_Build_IRequest
{
	const RESPONSE_TYPE = 'avs';
	const DEFAULT_SHIPPING_METHOD ='Unknown';
	const PHYSICAL_SHIPMENT_TYPE = 'physical';
	const VIRTUAL_SHIPMENT_TYPE = 'virtual';
	const VIRTUAL_SHIPPING_METHOD = 'EMAIL';

	/**
	 * Build the Risk Insight request payload.
	 *
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	public function build();
}
