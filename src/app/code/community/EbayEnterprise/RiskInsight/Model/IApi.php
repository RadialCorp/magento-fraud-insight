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

interface EbayEnterprise_RiskInsight_Model_IApi
{
	/**
	 * Get the request payload object.
	 * Initially, create and return a new empty payload
	 * of the type of payload for the configured service.
	 * (Users should not rely on the mutability of the returned object;
	 * Use `setRequestBody` to ensure a payload is attached for sending.)
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	public function getRequestBody();

	/**
	 * Set the payload for the configured request.
	 * This is the only way to guarantee an api has
	 * a payload to send.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload $payload
	 * @return self
	 */
	public function setRequestBody(EbayEnterprise_RiskInsight_Model_IPayload $payload);

	/**
	 * Send the request.
	 * May validate the payload before sending.
	 *
	 * @throws EbayEnterprise_RiskInsight_Model_Exception_Invalid_Payload_Exception
	 * @return self
	 */
	public function send();

	/**
	 * Retrieve the response payload.
	 * May validate the payload before delivering.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	public function getResponseBody();
}
