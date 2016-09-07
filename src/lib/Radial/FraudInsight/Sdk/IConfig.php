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

interface Radial_FraudInsight_Sdk_IConfig
{
	/**
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	public function getRequest();

	/**
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @return self
	 */
	public function setRequest(Radial_FraudInsight_Sdk_IPayload $request);

	/**
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	public function getResponse();

	/**
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @return self
	 */
	public function setResponse(Radial_FraudInsight_Sdk_IPayload $response);

	/**
	 * @return Radial_FraudInsight_Sdk_IPayload
	 */
	public function getError();

	/**
	 * @param  Radial_FraudInsight_Sdk_IPayload
	 * @return self
	 */
	public function setError(Radial_FraudInsight_Sdk_IPayload $error);
}
