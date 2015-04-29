<?php
/**
 * Copyright (c) 2015 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

interface EbayEnterprise_RiskInsight_Sdk_IPayments extends Countable, Iterator, ArrayAccess, EbayEnterprise_RiskInsight_Sdk_IIterable
{
	const PAYMENT_MODEL = 'EbayEnterprise_RiskInsight_Sdk_Payment';
	const ROOT_NODE = 'FormOfPayments';
	const SUBPAYLOAD_XPATH = 'FormOfPayment';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * get an empty payment
	 *
	 * @return EbayEnterprise_RiskInsight_Sdk_IPayment
	 */
	public function getEmptyPayment();
}
