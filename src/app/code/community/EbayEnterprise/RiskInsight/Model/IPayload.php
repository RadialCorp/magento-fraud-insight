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

interface EbayEnterprise_RiskInsight_Model_IPayload
{
	/**
	 * Return the string form of the payload data for transmission.
	 * Validation is implied.
	 *
	 * @throws Exception\InvalidPayload
	 * @return string
	 */
	public function serialize();

	/**
	 * Fill out this payload object with data from the supplied string.
	 *
	 * @throws Exception\InvalidPayload
	 * @param string $string
	 * @return self
	 */
	public function deserialize($string);
}
