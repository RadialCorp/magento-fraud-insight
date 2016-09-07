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

interface Radial_FraudInsight_Sdk_Http_IHeaders extends Countable, Iterator, ArrayAccess, Radial_FraudInsight_Sdk_IIterable
{
	const HTTP_HEADER_MODEL = 'Radial_FraudInsight_Sdk_Http_Header';
	const ROOT_NODE = 'HttpHeaders';
	const SUBPAYLOAD_XPATH = 'HttpHeader';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * get an empty http header
	 *
	 * @return Radial_FraudInsight_Sdk_Http_IHeader
	 */
	public function getEmptyHttpHeader();
}
