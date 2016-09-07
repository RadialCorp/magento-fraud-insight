<?php
/**
 * Copyright (c) 2013-2016 Radial Commerce Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2013-2016 Radial Commerce Inc. (http://www.radial.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Radial_FraudInsight_Model_Cron_Feedback
	implements Radial_FraudInsight_Model_Cron_IFeedback
{
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;
	/** @var Radial_FraudInsight_Helper_Config */
	protected $_config;

	/**
	 * @param array $initParams optional keys:
	 *                          - 'helper' => Radial_FraudInsight_Helper_Data
	 *                          - 'config' => Radial_FraudInsight_Helper_Config
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_helper, $this->_config) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('radial_fraudinsight')),
			$this->_nullCoalesce($initParams, 'config', Mage::helper('radial_fraudinsight/config'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Radial_FraudInsight_Helper_Data
	 * @param  Radial_FraudInsight_Helper_Config
	 * @return array
	 */
	protected function _checkTypes(
		Radial_FraudInsight_Helper_Data $helper,
		Radial_FraudInsight_Helper_Config $config
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
	 * @see Radial_FraudInsight_Model_Cron_IFeedback::process()
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
	 * @param  Radial_FraudInsight_Model_Resource_Risk_Insight_Collection
	 * @return self
	 */
	protected function _processFeebackOrderCollection(Radial_FraudInsight_Model_Resource_Risk_Insight_Collection $insightCollection)
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
	 * @param  Radial_FraudInsight_Model_Risk_Insight
	 * @param  Mage_Sales_Model_Order
	 * @return self
	 */
	protected function _processOrderFeedback(
		Radial_FraudInsight_Model_Risk_Insight $insight,
		Mage_Sales_Model_Order $order
	)
	{
		Mage::getModel('radial_fraudinsight/send_feedback', array(
			'order' => $order,
			'insight' => $insight,
		))->send();
		return $this;
	}
}
