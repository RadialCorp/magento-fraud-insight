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

class Radial_FraudInsight_Sdk_Http_Header
	extends Radial_FraudInsight_Sdk_Payload
	implements Radial_FraudInsight_Sdk_Http_IHeader
{
	/** @var string */
	protected $_header;
	/** @var string */
	protected $_name;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->_extractionPaths = array(
			'setHeader' => 'string(.)',
			'setName' => 'string(@name)',
		);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Http_IHeader::getDeviceIP()
	 */
	public function getHeader()
	{
		return $this->_header;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Http_IHeader::setHeader()
	 */
	public function setHeader($header)
	{
		$this->_header = $header;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Http_IHeader::getName()
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Http_IHeader::setName()
	 */
	public function setName($name)
	{
		$this->_name = $name;
		return $this;
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
		return $this->getHeader();
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootAttributes()
	 */
	protected function _getRootAttributes()
	{
		return array(
			'name' => $this->getName(),
		);
	}
}
