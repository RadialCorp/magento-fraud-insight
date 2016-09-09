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

class Radial_FraudInsight_Sdk_Person_Name
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_Person_IName
{
	/** @var string */
	protected $_lastName;
	/** @var string */
	protected $_middleName;
	/** @var string */
	protected $_firstName;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->_extractionPaths = array(
			'setFirstName' => 'string(x:FirstName)',
		);
		$this->_optionalExtractionPaths = array(
			'setLastName' => 'x:LastName',
			'setMiddleName' => 'x:MiddleName',
		);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Person_IName::getLastName()
	 */
	public function getLastName()
	{
		return $this->_lastName;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Person_IName::setLastName()
	 */
	public function setLastName($lastName)
	{
		$this->_lastName = $lastName;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Person_IName::getMiddleName()
	 */
	public function getMiddleName()
	{
		return $this->_middleName;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Person_IName::setMiddleName()
	 */
	public function setMiddleName($middleName)
	{
		$this->_middleName = $middleName;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Person_IName::getFirstName()
	 */
	public function getFirstName()
	{
		return $this->_firstName;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Person_IName::setFirstName()
	 */
	public function setFirstName($firstName)
	{
		$this->_firstName = $firstName;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_canSerialize()
	 */
	protected function _canSerialize()
	{
		return (trim($this->getFirstName()) !== '');
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootNodeName()
	 */
	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getXmlNamespace()
	 */
	protected function _getXmlNamespace()
	{
		return self::XML_NS;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_serializeContents()
	 */
	protected function _serializeContents()
	{
		return $this->_serializeOptionalValue('LastName', $this->getLastName())
			. $this->_serializeOptionalValue('MiddleName', $this->getMiddleName())
			. $this->_serializeNode('FirstName', $this->getFirstName());
	}
}
