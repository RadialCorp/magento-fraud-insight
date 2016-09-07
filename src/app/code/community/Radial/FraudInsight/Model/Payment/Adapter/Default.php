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

class Radial_FraudInsight_Model_Payment_Adapter_Default
	extends Radial_FraudInsight_Model_Payment_Adapter_Type
{
	protected function _initialize()
	{
		$payment = $this->_order->getPayment();
		$this->setExtractCardHolderName($payment->getCcOwner())
			->setExtractPaymentAccountUniqueId($this->_helper->getAccountUniqueId($payment))
			->setExtractIsToken(static::IS_TOKEN)
			->setExtractPaymentAccountBin($this->_helper->getAccountBin($payment))
			->setExtractExpireDate($this->_helper->getPaymentExpireDate($payment))
			->setExtractCardType($this->_helper->getMapRiskInsightPaymentMethod($payment))
			->setExtractTransactionResponses(array());
		return $this;
	}
}
