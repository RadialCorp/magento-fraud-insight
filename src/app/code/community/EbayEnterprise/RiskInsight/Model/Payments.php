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

class EbayEnterprise_RiskInsight_Model_Payments
	extends EbayEnterprise_RiskInsight_Model_Iterable
	implements EbayEnterprise_RiskInsight_Model_IPayments
{
	/**
	 * Get an empty instance of the payment payload
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayment
	 */
	public function getEmptyPayment()
	{
		return $this->_buildPayloadForModel(static::PAYMENT_MODEL);
	}

	protected function _getNewSubpayload()
	{
		return $this->getEmptyPayment();
	}

	protected function _getSubpayloadXPath()
	{
		return 'x:' . static::SUBPAYLOAD_XPATH;
	}

	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	protected function _getXmlNamespace()
	{
		return self::XML_NS;
	}
}
