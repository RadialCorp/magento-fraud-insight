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

/**
 * @codeCoverageIgnore
 */
class Radial_FraudInsight_Model_Payment_Adapter_Type
	extends Radial_FraudInsight_Model_Payment_Adapter_Type_Abstract
	implements Radial_FraudInsight_Model_Payment_Adapter_IType
{
	/** @var Mage_Sales_Model_Order */
	protected $_order;
	/** @var Radial_FraudInsight_Helper_Data */
	protected $_helper;
	/** @var string | null */
	protected $_extractCardHolderName;
	/** @var string | null */
	protected $_extractPaymentAccountUniqueId;
	/** @var string | null */
	protected $_extractPaymentAccountBin;
	/** @var string | null */
	protected $_extractExpireDate;
	/** @var string | null */
	protected $_extractCardType;
	/** @var string | null */
	protected $_extractTransactionResponses;
	/** @var string */
	protected $_extractIsToken;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'order' => Mage_Sales_Model_Order
	 *                          - 'helper' => Radial_FraudInsight_Helper_Data
	 */
	public function __construct(array $initParams=array())
	{
		list($this->_order, $this->_helper) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'order', $initParams['order']),
			$this->_nullCoalesce($initParams, 'helper', Mage::helper('radial_fraudinsight'))
		);
		$this->_initialize();
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  Mage_Sales_Model_Order
	 * @param  Radial_FraudInsight_Helper_Data
	 * @return array
	 */
	protected function _checkTypes(
		Mage_Sales_Model_Order $order,
		Radial_FraudInsight_Helper_Data $helper
	) {
		return array($order, $helper);
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

	public function getExtractCardHolderName()
	{
		return $this->_extractCardHolderName;
	}

	public function setExtractCardHolderName($cardHolderName)
	{
		$this->_extractCardHolderName = $cardHolderName;
		return $this;
	}

	public function getExtractPaymentAccountUniqueId()
	{
		return $this->_extractPaymentAccountUniqueId;
	}

	public function setExtractPaymentAccountUniqueId($paymentAccountUniqueId)
	{
		$this->_extractPaymentAccountUniqueId = $paymentAccountUniqueId;
		return $this;
	}

	public function getExtractPaymentAccountBin()
	{
		return $this->_extractPaymentAccountBin;
	}

	public function setExtractPaymentAccountBin($paymentAccountBin)
	{
		$this->_extractPaymentAccountBin = $paymentAccountBin;
		return $this;
	}

	public function getExtractExpireDate()
	{
		return $this->_extractExpireDate;
	}

	public function setExtractExpireDate($expireDate)
	{
		$this->_extractExpireDate = $expireDate;
		return $this;
	}

	public function getExtractCardType()
	{
		return $this->_extractCardType;
	}

	public function setExtractCardType($cardType)
	{
		$this->_extractCardType = $cardType;
		return $this;
	}

	public function getExtractTransactionResponses()
	{
		return $this->_extractTransactionResponses;
	}

	public function setExtractTransactionResponses(array $transactionResponses)
	{
		$this->_extractTransactionResponses = $transactionResponses;
		return $this;
	}

	public function getExtractIsToken()
	{
		return $this->_extractIsToken;
	}

	public function setExtractIsToken($isToken)
	{
		$this->_extractIsToken = $isToken;
		return $this;
	}

	protected function _initialize()
	{
		return $this;
	}
}
