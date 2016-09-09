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

interface Radial_FraudInsight_Sdk_IAddress extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'Address';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * xsd restrictions: 1-100 characters
	 * @return string
	 */
	public function getLineA();

	/**
	 * @param  string
	 * @return self
	 */
	public function setLineA($lineA);

	/**
	 * @return string
	 */
	public function getLineB();

	/**
	 * @param  string
	 * @return self
	 */
	public function setLineB($lineB);

	/**
	 * @return string
	 */
	public function getLineC();

	/**
	 * @param  string
	 * @return self
	 */
	public function setLineC($lineC);

	/**
	 * @return string
	 */
	public function getLineD();

	/**
	 * @param  string
	 * @return self
	 */
	public function setLineD($lineD);

	/**
	 * @return string
	 */
	public function getCity();

	/**
	 * @param  string
	 * @return self
	 */
	public function setCity($city);

	/**
	 * @return string
	 */
	public function getPostalCode();

	/**
	 * @param  string
	 * @return self
	 */
	public function setPostalCode($postalCode);

	/**
	 * @return string
	 */
	public function getMainDivisionCode();

	/**
	 * @param  string
	 * @return self
	 */
	public function setMainDivisionCode($mainDivisionCode);

	/**
	 * @return string
	 */
	public function getCountryCode();

	/**
	 * @param  string
	 * @return self
	 */
	public function setCountryCode($countryCode);
}
