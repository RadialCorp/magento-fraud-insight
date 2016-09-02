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

interface Radial_FraudInsight_Sdk_IError extends Radial_FraudInsight_Sdk_Payload_ITop
{
	const ROOT_NODE = 'RiskInsightErrorResponse';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const XSD = 'RiskInsightErrorResponse.xsd';

	/**
	 * @return string
	 */
	public function getErrorCode();

	/**
	 * @param  string
	 * @return self
	 */
	public function setErrorCode($errorCode);

	/**
	 * @return string
	 */
	public function getErrorDescription();

	/**
	 * @param  string
	 * @return self
	 */
	public function setErrorDescription($errorDescription);

	/**
	 * @return string
	 */
	public function getExceptionLog();

	/**
	 * @param  string
	 * @return self
	 */
	public function setExceptionLog($exceptionLog);
}
