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

class Radial_FraudInsight_Sdk_Helper
	implements Radial_FraudInsight_Sdk_IHelper
{
	/**
	 * @see Radial_FraudInsight_Sdk_IHelper::convertStringToBoolean()
	 */
	public function convertStringToBoolean($string)
	{
		if (!is_string($string)) {
			return null;
		}
		$string = strtolower($string);
		switch ($string) {
			case 'true':
			case '1':
				return true;
			case 'false':
			case '0':
				return false;
		}
		return null;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IHelper::formatAmount()
	 */
	public function formatAmount($amount)
	{
		return sprintf('%01.2F', $amount);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IHelper::getPayloadAsXPath()
	 */
	public function getPayloadAsXPath($xmlString, $nameSpace)
	{
		$xpath = new DOMXPath($this->getPayloadAsDoc($xmlString));
		$xpath->registerNamespace('x', $nameSpace);
		return $xpath;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IHelper::getPayloadAsDoc()
	 */
	public function getPayloadAsDoc($xmlString)
	{
		$d = new DOMDocument();
		// Suppress the warning error that occurred when the passed in xml string is malformed
		// instead throw a custom exception. Reference http://stackoverflow.com/questions/1759069/
		if(@$d->loadXML($xmlString) === false) {
			$exceptionMessage = "The XML string ($xmlString) is invalid";
			throw Mage::exception('Radial_FraudInsight_Sdk_Exception_Invalid_Xml', $exceptionMessage);
		}
		$d->encoding = 'utf-8';
		$d->formatOutput = false;
		$d->preserveWhiteSpace = false;
		$d->normalizeDocument();
		return $d;
	}

	/**
	 * @see Mage_Core_Helper_Abstract::escapeHtml()
	 */
	public function escapeHtml($data)
	{
		return Mage::helper('core')->escapeHtml($data);
	}
}
