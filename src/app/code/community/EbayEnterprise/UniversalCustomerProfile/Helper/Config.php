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

/**
 * @codeCoverageIgnore
 */
class EbayEnterprise_UniversalCustomerProfile_Helper_Config
{
	const GENERAL_STORE_ID = 'universal_customer_profile/general/store_id';
	const API_HOSTNAME = 'universal_customer_profile/api/hostname';
	const API_KEY = 'universal_customer_profile/api/key';
	const API_TIMEOUT = 'universal_customer_profile/api/timeout';

	/**
	 * retrieve the FraudNet Store id from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getStoreId($store=null)
	{
		return Mage::getStoreConfig(static::GENERAL_STORE_ID, $store);
	}
	/**
	 * retrieve the api hostname from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getHostname($store=null)
	{
		return Mage::getStoreConfig(static::API_HOSTNAME, $store);
	}
	/**
	 * retrieve the api encrypted key from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getKey($store=null)
	{
		return Mage::getStoreConfig(static::API_KEY, $store);
	}
	/**
	 * retrieve the api timeout setting from store config
	 * @param mixed $store
	 * @return string
	 */
	public function getTimeout($store=null)
	{
		return Mage::getStoreConfig(static::API_TIMEOUT, $store);
	}
}
