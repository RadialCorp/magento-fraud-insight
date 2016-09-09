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

/**
 * @codeCoverageIgnore
 */
class Radial_FraudInsight_Sdk_Info
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_IInfo
{
	/** @var Radial_FraudInsight_Sdk_Person_IName */
	protected $_personName;
	/** @var string */
	protected $_email;
	/** @var Radial_FraudInsight_Sdk_ITelephone */
	protected $_telephone;
	/** @var Radial_FraudInsight_Sdk_IAddress */
	protected $_address;

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::getPersonName()
	 */
	public function getPersonName()
	{
		return $this->_personName;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::setPersonName()
	 */
	public function setPersonName(Radial_FraudInsight_Sdk_Person_IName $personName)
	{
		$this->_personName = $personName;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::getEmail()
	 */
	public function getEmail()
	{
		return $this->_email;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::setEmail()
	 */
	public function setEmail($email)
	{
		$this->_email = $email;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::getTelephone()
	 */
	public function getTelephone()
	{
		return $this->_telephone;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::setTelephone()
	 */
	public function setTelephone(Radial_FraudInsight_Sdk_ITelephone $telephone)
	{
		$this->_telephone = $telephone;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::getAddress()
	 */
	public function getAddress()
	{
		return $this->_address;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IInfo::setAddress()
	 */
	public function setAddress(Radial_FraudInsight_Sdk_IAddress $address)
	{
		$this->_address = $address;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootNodeName()
	 */
	protected function _getRootNodeName()
	{
		return '';
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getXmlNamespace()
	 */
	protected function _getXmlNamespace()
	{
		return '';
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_serializeContents()
	 */
	protected function _serializeContents()
	{
		return '';
	}
}
