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

interface EbayEnterprise_RiskInsight_Model_IAddress extends EbayEnterprise_RiskInsight_Model_IPayload
{
	const ROOT_NODE = 'Address';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * xsd restrictions: 1-100 characters
	 * @return string
	 */
	public function getLineA();
	/**
	 * @param  string $lineA
	 * @return self
	 */
	public function setLineA($lineA);
	/**
	 * @return string
	 */
	public function getLineB();
	/**
	 * @param  string $lineB
	 * @return self
	 */
	public function setLineB($lineB);
	/**
	 * @return string
	 */
	public function getLineC();
	/**
	 * @param  string $lineC
	 * @return self
	 */
	public function setLineC($lineC);
	/**
	 * @return string
	 */
	public function getLineD();
	/**
	 * @param  string $lineD
	 * @return self
	 */
	public function setLineD($lineD);
	/**
	 * @return string
	 */
	public function getCity();
	/**
	 * @param  string $city
	 * @return self
	 */
	public function setCity($city);
	/**
	 * @return string
	 */
	public function getPostalCode();
	/**
	 * @param  string $postalCode
	 * @return self
	 */
	public function setPostalCode($postalCode);
	/**
	 * @return string
	 */
	public function getMainDivisionCode();
	/**
	 * @param  string $mainDivisionCode
	 * @return self
	 */
	public function setMainDivisionCode($mainDivisionCode);
	/**
	 * @return string
	 */
	public function getCountryCode();
	/**
	 * @param  string $countryCode
	 * @return self
	 */
	public function setCountryCode($countryCode);
}
