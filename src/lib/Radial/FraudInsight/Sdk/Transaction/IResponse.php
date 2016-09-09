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

interface Radial_FraudInsight_Sdk_Transaction_IResponse extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'TransactionResponse';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * value comes from list: {'M'|'N'|'confirmed'|'verified'|'X'}
	 *
	 * @return string
	 */
	public function getResponse();
	/**
	 * @param  string
	 * @return self
	 */
	public function setResponse($response);

	/**
	 * Each transaction response specifies the type of transaction response and the value returned.
	 * Sample Data:
	 * <TransactionResponse ResponseType="avsAddr"> Y</TransactionResponse>
	 * <TransactionResponse ResponseType="avsZip"> Y</TransactionResponse>
	 * <TransactionResponse ResponseType="cvv2"> M</TransactionResponse>
	 *
	 * @return string
	 */
	public function getResponseType();

	/**
	 * @param  string
	 * @return self
	 */
	public function setResponseType($responseType);
}
