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

interface Radial_FraudInsight_Sdk_ITelephone extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'Telephone';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * @return string
	 */
	public function getCountryCode();

	/**
	 * @param  string
	 * @return self
	 */
	public function setCountryCode($countryCode);

	/**
	 * @return string
	 */
	public function getAreaCode();

	/**
	 * @param  string
	 * @return self
	 */
	public function setAreaCode($areaCode);

	/**
	 * @return string
	 */
	public function getNumber();

	/**
	 * @param  string
	 * @return self
	 */
	public function setNumber($number);

	/**
	 * @return string
	 */
	public function getExtension();

	/**
	 * @param  string
	 * @return self
	 */
	public function setExtension($extension);
}
