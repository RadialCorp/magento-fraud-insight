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

interface Radial_FraudInsight_Sdk_IPayload
{
	/**
	 * Return the string form of the payload data for transmission.
	 * Validation is implied.
	 *
	 * @throws Radial_FraudInsight_Sdk_Exception_Invalid_Payload_Exception
	 * @return string
	 */
	public function serialize();

	/**
	 * Fill out this payload object with data from the supplied string.
	 *
	 * @throws Radial_FraudInsight_Sdk_Exception_Invalid_Payload_Exception
	 * @param string
	 * @return self
	 */
	public function deserialize($string);
}
