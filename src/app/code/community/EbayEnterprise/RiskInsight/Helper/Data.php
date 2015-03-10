<?php
/**
 * Copyright (c) 2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

class EbayEnterprise_RiskInsight_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Convert "true", "false", "1" or "0" to boolean
	 * Everything else returns null
	 *
	 * @param  string $string
	 * @return bool|null
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
	 * Consistent formatting of amounts.
	 *
	 * @param float
	 * @return string
	 */
	public function formatAmount($amount)
	{
		return sprintf('%01.2F', $amount);
	}

	/**
	 * Load the payload XML into a DOMXPath for querying.
	 * @param  string $xmlString
	 * @param  string $namespace
	 * @return DOMXPath
	 */
	public function getPayloadAsXPath($xmlString, $namespace)
	{
		$xpath = new DOMXPath($this->getPayloadAsDoc($xmlString));
		$xpath->registerNamespace('x', $namespace);
		return $xpath;
	}

	/**
	 * Load the payload XML into a DOMDocument
	 * @param  string $xmlString
	 * @return DOMDocument
	 */
	public function getPayloadAsDoc($xmlString)
	{
		$d = new DOMDocument();
		try {
			$d->loadXML($xmlString);
		} catch (Exception $e) {
			throw $e;
		}
		return $d;
	}
	/**
	 * Get all header data.
	 * @return array
	 */
	public function getHeaderData()
	{
		$headers = array();
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}

	/**
	 * Get a collection of risk insight object where ucp request has been sent.
	 * @return array
	 */
	public function getRiskInsightCollection()
	{
		return Mage::getResourceModel('ebayenterprise_riskinsight/risk_insight_collection')
			->addFieldToFilter('is_request_sent', 0);
	}

	/**
	 * Getting a collection of sales/order object that's in an array of increment ids.
	 * @param  array  $incrementIds
	 * @return Mage_Sales_Model_Resource_Order_Collection
	 */
	public function getOrderCollectionByIncrementIds(array $incrementIds=array())
	{
		return Mage::getResourceModel('sales/order_collection')
			->addFieldToFilter('increment_id', array('in' => $incrementIds));
	}
}
