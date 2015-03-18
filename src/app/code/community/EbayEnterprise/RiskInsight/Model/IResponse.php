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

interface EbayEnterprise_RiskInsight_Model_IResponse extends EbayEnterprise_RiskInsight_Model_Payload_ITop
{
	const ROOT_NODE = 'RiskInsightResponse';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const XSD = 'RiskInsightResponse.xsd';

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
	 * Order number/id. Must be unique across all gsi systems.
	 *
	 * xsd restrictions: >= 1 characters
	 * @return string
	 */
	public function getOrderId();

	/**
	 * @param  string $orderId
	 * @return self
	 */
	public function setOrderId($orderId);

	/**
	 * Store code/identifier. Maps to a fraudNet model code in addition to being
	 * sent to fraudNet. New store codes will require configuration both in
	 * risk service and fraudNet
	 *
	 * xsd restrictions: >= 1 characters
	 * @return string
	 */
	public function getStoreId();

	/**
	 * @param  string $storeId
	 * @return self
	 */
	public function setStoreId($storeId);

	/**
	 * @return string
	 */
	public function getResponseReasonCode();

	/**
	 * @param  string $responseReasonCode
	 * @return self
	 */
	public function setResponseReasonCode($responseReasonCode);

	/**
	 * @return string
	 */
	public function getResponseReasonCodeDescription();

	/**
	 * @param  string $responseReasonCodeDescription
	 * @return self
	 */
	public function setResponseReasonCodeDescription($responseReasonCodeDescription);
}
