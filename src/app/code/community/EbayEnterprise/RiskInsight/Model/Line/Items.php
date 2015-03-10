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

class EbayEnterprise_RiskInsight_Model_Line_Items
	extends EbayEnterprise_RiskInsight_Model_Iterable
	implements EbayEnterprise_RiskInsight_Model_Line_IItems
{
	/**
	 * Get an empty instance of the line item payload
	 * @return EbayEnterprise_RiskInsight_Model_Line_IItem
	 */
	public function getEmptyLineItem()
	{
		return $this->_buildPayloadForModel(static::LINE_ITEM_MODEL);
	}

	protected function _getNewSubpayload()
	{
		return $this->getEmptyLineItem();
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
