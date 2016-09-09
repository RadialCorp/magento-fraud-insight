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
