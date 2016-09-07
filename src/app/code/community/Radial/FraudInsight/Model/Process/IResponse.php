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

interface Radial_FraudInsight_Model_Process_IResponse
{
	const STATUS_RISK_REVIEW = 'risk_review';
	const STATUS_RISK_CANCELED = 'risk_canceled';
	const RESPONSE_CODE_HIGH = 'HIGH';
	const RESPONSE_CODE_MEDIUM = 'MEDIUM';
	const RESPONSE_CODE_LOW = 'LOW';
	const RESPONSE_CODE_UNKNOWN = 'UNKNOWN';

	/**
	 * Process the response payload.
	 *
	 * @return self
	 */
	public function process();
}
