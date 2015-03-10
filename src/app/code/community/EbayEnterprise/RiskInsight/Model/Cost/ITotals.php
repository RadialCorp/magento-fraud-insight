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

interface EbayEnterprise_RiskInsight_Model_Cost_ITotals extends EbayEnterprise_RiskInsight_Model_IPayload
{
	const ROOT_NODE = 'CostTotals';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * @return string
	 */
	public function getCurrencyCode();
	/**
	 * @param  string $currencyCode
	 * @return self
	 */
	public function setCurrencyCode($currencyCode);
	/**
	 * @return float
	 */
	public function getAmountBeforeTax();
	/**
	 * @param  float $amountBeforeTax
	 * @return self
	 */
	public function setAmountBeforeTax($amountBeforeTax);
	/**
	 * @return float
	 */
	public function getAmountAfterTax();
	/**
	 * @param  float $amountAfterTax
	 * @return self
	 */
	public function setAmountAfterTax($amountAfterTax);
}
