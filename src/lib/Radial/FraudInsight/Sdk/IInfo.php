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

interface Radial_FraudInsight_Sdk_IInfo extends Radial_FraudInsight_Sdk_IPayload
{
	/**
	 * @return Radial_FraudInsight_Sdk_Person_IName
	 */
	public function getPersonName();

	/**
	 * @param  Radial_FraudInsight_Sdk_Person_IName
	 * @return self
	 */
	public function setPersonName(Radial_FraudInsight_Sdk_Person_IName $personName);

	/**
	 * @return string
	 */
	public function getEmail();

	/**
	 * @param  string
	 * @return self
	 */
	public function setEmail($email);

	/**
	 * @return Radial_FraudInsight_Sdk_ITelephone
	 */
	public function getTelephone();

	/**
	 * @param  Radial_FraudInsight_Sdk_ITelephone
	 * @return self
	 */
	public function setTelephone(Radial_FraudInsight_Sdk_ITelephone $telephone);

	/**
	 * @return Radial_FraudInsight_Sdk_IAddress
	 */
	public function getAddress();

	/**
	 * @param  Radial_FraudInsight_Sdk_IAddress
	 * @return self
	 */
	public function setAddress(Radial_FraudInsight_Sdk_IAddress $address);
}
