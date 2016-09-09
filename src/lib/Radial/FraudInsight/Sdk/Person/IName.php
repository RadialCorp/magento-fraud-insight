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

interface Radial_FraudInsight_Sdk_Person_IName extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'PersonName';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';

	/**
	 * @return string
	 */
	public function getLastName();

	/**
	 * @param  string
	 * @return self
	 */
	public function setLastName($lastName);

	/**
	 * xsd restrictions: <= 10 characters
	 * @return string
	 */
	public function getMiddleName();

	/**
	 * @param  string
	 * @return self
	 */
	public function setMiddleName($middleName);

	/**
	 * @return string
	 */
	public function getFirstName();

	/**
	 * @param  string
	 * @return self
	 */
	public function setFirstName($firstName);
}
