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

interface Radial_FraudInsight_Sdk_IRequest extends Radial_FraudInsight_Sdk_Payload_ITop
{
	const ROOT_NODE = 'RiskInsightRequest';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const XSD = 'RiskInsightRequest.xsd';
	const ORDER_MODEL ='Radial_FraudInsight_Sdk_Order';

	/**
	 * The primary language ID used in the XML message.
	 * Sample Data: en-US
	 * Implementation Notes: For future internationalization support.
	 *
	 * value comes from list: {'en'}
	 * @return string
	 */
	public function getPrimaryLangId();

	/**
	 * @param  string
	 * @return self
	 */
	public function setPrimaryLangId($primaryLangId);

	/**
	 * Contain order detail information.
	 *
	 * @return Radial_FraudInsight_Sdk_IOrder
	 */
	public function getOrder();

	/**
	 * @param  Radial_FraudInsight_Sdk_IOrder
	 * @return self
	 */
	public function setOrder(Radial_FraudInsight_Sdk_IOrder $order);
}
