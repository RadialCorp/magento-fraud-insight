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

class EbayEnterprise_RiskInsight_Model_Cron_Feedback
	implements EbayEnterprise_RiskInsight_Model_Cron_IFeedback
{
	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helper;
	/** @var EbayEnterprise_RiskInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams optional keys:
	 *                          - 'helper' => EbayEnterprise_RiskInsight_Helper_Data
	 *                          - 'config' => EbayEnterprise_RiskInsight_Helper_Config
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_helper, $this->_config) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('ebayenterprise_riskinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('ebayenterprise_riskinsight/config'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  EbayEnterprise_RiskInsight_Helper_Data
	 * @param  EbayEnterprise_RiskInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		EbayEnterprise_RiskInsight_Helper_Data $helper,
		EbayEnterprise_RiskInsight_Helper_Config $config
	) {
		return array($helper, $config);
	}

	/**
	 * Return the value at field in array if it exists. Otherwise, use the default value.
	 *
	 * @param  array
	 * @param  string | int $field Valid array key
	 * @param  mixed
	 * @return mixed
	 */
	protected function _nullCoalesce(array $arr, $field, $default)
	{
		return isset($arr[$field]) ? $arr[$field] : $default;
	}

	/**
	 * @see EbayEnterprise_RiskInsight_Model_Cron_IFeedback::process()
	 */
	public function process()
	{
		$this->_processFeebackOrderCollection($this->_helper->getFeedbackOrderCollection());
		return $this;
	}

	/**
	 * Use the passed in collection of risk insight objects to get a collection of sales order objects.
	 * Loop through each risk insight object in the collection to get the associated sales order object from
	 * sales order object collection. Determine if the sales order object is valid to send feedback request.
	 * If so, continue to process order feedback.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_Resource_Risk_Insight_Collection
	 * @return self
	 */
	protected function _processFeebackOrderCollection(EbayEnterprise_RiskInsight_Model_Resource_Risk_Insight_Collection $insightCollection)
	{
		/** @var Mage_Sales_Model_Resource_Order_Collection **/
		$orderCollection = $this->_helper->getOrderCollectionByIncrementIds(
			$insightCollection->getColumnValues('order_increment_id')
		);
		foreach ($insightCollection as $insight) {
			$order = $orderCollection->getItemByColumnValue('increment_id', $insight->getOrderIncrementId());
			if ($order && $this->_helper->isOrderInAStateToSendFeedback($order)) {
				$this->_processOrderFeedback($insight, $order);
			}
		}
		return $this;
	}

	/**
	 * Send risk insight feedback request and then process the response accordingly.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _processOrderFeedback(
		EbayEnterprise_RiskInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order
	)
	{
		Mage::getModel('ebayenterprise_riskinsight/send_feedback', array(
			'order' => $order,
			'insight' => $insight,
		))->send();
		return $this;
	}
}
