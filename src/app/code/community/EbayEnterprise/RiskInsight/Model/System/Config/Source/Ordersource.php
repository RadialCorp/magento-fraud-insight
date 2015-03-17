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

class EbayEnterprise_RiskInsight_Model_System_Config_Source_Ordersource
{
	const WEBSTORE = 'WEBSTORE';
	const DASHBOARD = 'DASHBOARD';
	const KIOSK = 'KIOSK';
	const MOBILE = 'MOBILE';
	const OTHER = 'OTHER';
	/**
	 * Get category attributes
	 * @return array
	 */
	public function toOptionArray()
	{
		$options = array(static::WEBSTORE, static::DASHBOARD, static::KIOSK, static::MOBILE, static::OTHER);
		$optionValues = array(array('value' => '', 'label' => ''));
		foreach ($options as $option) {
			$optionValues[] = array('value' => $option, 'label' => $option);
		}
		return $optionValues;
	}
}
