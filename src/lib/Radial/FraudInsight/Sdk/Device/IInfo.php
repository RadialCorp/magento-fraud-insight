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

interface Radial_FraudInsight_Sdk_Device_IInfo extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'DeviceInfo';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const HTTP_HEADERS_MODEL ='Radial_FraudInsight_Sdk_Http_Headers';

	/**
	 * xsd restrictions: <= 15 characters
	 * @return string
	 */
	public function getDeviceIP();

	/**
	 * @param  string
	 * @return self
	 */
	public function setDeviceIP($deviceIP);

	/**
	 * @return Radial_FraudInsight_Sdk_Http_Headers
	 */
	public function getHttpHeaders();

	/**
	 * @param  Radial_FraudInsight_Sdk_Http_Headers
	 * @return self
	 */
	public function setHttpHeaders(Radial_FraudInsight_Sdk_Http_Headers $httpHeaders);
}
