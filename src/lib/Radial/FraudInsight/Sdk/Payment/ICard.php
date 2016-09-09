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

interface Radial_FraudInsight_Sdk_Payment_ICard extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'PaymentCard';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * @return string
	 */
	public function getCardHolderName();

	/**
	 * @param  string
	 * @return self
	 */
	public function setCardHolderName($cardHolderName);

	/**
	 * Either a raw PaymentAccountNumber(PAN) or a token representing a PAN.
	 * The type includes an attribute, isToken, to indicate if the PAN is tokenized.
	 *
	 * @return string
	 */
	public function getPaymentAccountUniqueId();

	/**
	 * @param  string
	 * @return self
	 */
	public function setPaymentAccountUniqueId($paymentAccountUniqueId);

	/**
	 * @return bool
	 */
	public function getIsToken();

	/**
	 * @param  bool
	 * @return self
	 */
	public function setIsToken($isToken);

	/**
	 * @return string
	 */
	public function getPaymentAccountBin();

	/**
	 * @param  string
	 * @return self
	 */
	public function setPaymentAccountBin($paymentAccountBin);

	/**
	 * @return string
	 */
	public function getExpireDate();

	/**
	 * @param  string
	 * @return self
	 */
	public function setExpireDate($expireDate);

	/**
	 * value comes from list: {'VC'|'MC'|'AM'|'DC'|'PY'|'BL'|'OI'|'GC'|'OGC'|'BC'|'CASH'}
	 *
	 * @return string
	 */
	public function getCardType();

	/**
	 * @param  string
	 * @return self
	 */
	public function setCardType($cardType);
}
