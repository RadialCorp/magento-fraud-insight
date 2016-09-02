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

interface Radial_FraudInsight_Sdk_Device_IInfo extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'DeviceInfo';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const HTTP_HEADERS_MODEL ='Radial_FraudInsight_Sdk_Http_Headers';

	/**
	 * xsd restrictions: <= 15 characters
	 * @return string
	 */
	public function getDeviceIP();

	/**
	 * @param  string
	 * @return self
	 */
	public function setDeviceIP($deviceIP);

	/**
	 * @return Radial_FraudInsight_Sdk_Http_Headers
	 */
	public function getHttpHeaders();

	/**
	 * @param  Radial_FraudInsight_Sdk_Http_Headers
	 * @return self
	 */
	public function setHttpHeaders(Radial_FraudInsight_Sdk_Http_Headers $httpHeaders);
}
