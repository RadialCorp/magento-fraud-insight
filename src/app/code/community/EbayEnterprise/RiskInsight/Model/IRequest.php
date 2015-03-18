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

interface EbayEnterprise_RiskInsight_Model_IRequest extends EbayEnterprise_RiskInsight_Model_Payload_ITop
{
	const ROOT_NODE = 'RiskInsightRequest';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const XSD = 'RiskInsightRequest.xsd';
	const ORDER_MODEL ='ebayenterprise_riskinsight/order';

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
	 * @param  string $primaryLangId
	 * @return self
	 */
	public function setPrimaryLangId($primaryLangId);

	/**
	 * Contain order detail information.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IOrder
	 */
	public function getOrder();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IOrder $order
	 * @return self
	 */
	public function setOrder(EbayEnterprise_RiskInsight_Model_IOrder $order);
}
