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

class EbayEnterprise_RiskInsight_Model_Total
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_ITotal
{
	/** @var EbayEnterprise_RiskInsight_Model_Cost_Totals */
	protected $_costTotals;

	public function __construct()
	{
		$this->setCostTotals($this->_buildPayloadForModel(static::COST_TOTALS_MODEL));
		$this->_subpayloadExtractionPaths = array(
			'setCostTotals' => 'x:CostTotals',
		);
	}

	public function getCostTotals()
	{
		return $this->_costTotals;
	}

	public function setCostTotals(EbayEnterprise_RiskInsight_Model_Cost_ITotals $costTotals)
	{
		$this->_costTotals = $costTotals;
		return $this;
	}

	protected function _canSerialize()
	{
		return (trim($this->getCostTotals()->serialize()) !== '');
	}

	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	protected function _getXmlNamespace()
	{
		return self::XML_NS;
	}

	protected function _serializeContents()
	{
		return $this->getCostTotals()->serialize();
	}
}
