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

class Radial_FraudInsight_Model_Payment_Adapter_Paypal_Express
	extends Radial_FraudInsight_Model_Payment_Adapter_Type
{
	protected function _initialize()
	{
		$payment = $this->_order->getPayment();
		$this->setExtractCardHolderName(null)
			->setExtractPaymentAccountUniqueId($this->_getExtractPaymentAccountUniqueId($payment))
			->setExtractIsToken(static::IS_NOT_TOKEN)
			->setExtractPaymentAccountBin(null)
			->setExtractExpireDate(null)
			->setExtractCardType($this->_helper->getPaymentMethodValueFromMap($payment->getMethod()))
			->setExtractTransactionResponses($this->_getPaypalTransactions($payment));
		return $this;
	}

	/**
	 * @return Mage_Paypal_Model_Info
	 */
	protected function _getInfoInstance()
	{
		return Mage::getModel('paypal/info');
	}

	/**
	 * @param  Mage_Payment_Model_Info
	 * @return Mage_Payment_Model_Method_Cc
	 */
	protected function _getPaypalInfo(Mage_Payment_Model_Info $payment)
	{
		return $this->_getInfoInstance()
			->getPaymentInfo($payment);
	}

	/**
	 * @param  Mage_Payment_Model_Info
	 * @return string | null
	 */
	protected function _getExtractPaymentAccountUniqueId(Mage_Payment_Model_Info $payment)
	{
		$info = $this->_getPaypalInfo($payment);
		return isset($info['paypal_payer_id']) ? $info['paypal_payer_id']['value'] : null;
	}

	/**
	 * @param  Mage_Payment_Model_Info
	 * @return array
	 */
	protected function _getPaypalTransactions(Mage_Payment_Model_Info $payment)
	{
		$info = $this->_getPaypalInfo($payment);
		return array(
			array('type' => 'PayPalPayer', 'response' => strtolower($info['paypal_payer_status']['value'])),
			array('type' => 'PayPalAddress', 'response' => strtolower($info['paypal_address_status']['value'])),
		);
	}
}
