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

interface Radial_FraudInsight_Sdk_IHelper
{
	/**
	 * Convert "true", "false", "1" or "0" to boolean
	 * Everything else returns null
	 *
	 * @param  string
	 * @return bool | null
	 */
	public function convertStringToBoolean($string);

	/**
	 * Consistent formatting of amounts.
	 *
	 * @param  float
	 * @return string
	 */
	public function formatAmount($amount);

	/**
	 * Load the payload XML into a DOMXPath for querying.
	 *
	 * @param  string
	 * @param  string
	 * @return DOMXPath
	 */
	public function getPayloadAsXPath($xmlString, $nameSpace);

	/**
	 * Load the payload XML into a DOMDocument
	 *
	 * @param  string
	 * @return DOMDocument
	 * @throws Radial_FraudInsight_Sdk_Exception_Invalid_Xml_Exception
	 */
	public function getPayloadAsDoc($xmlString);

}
