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

abstract class EbayEnterprise_RiskInsight_Model_Payload_Top
	extends EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_Payload_ITop
{
	/**
	 * Return the string form of the payload data for transmission.
	 * Validation is implied.
	 *
	 * @throws EbayEnterprise_RiskInsight_Model_Exception_Invalid_Payload_Exception
	 * @return string
	 */
	public function serialize()
	{
		$xmlString = sprintf(
			'<%s %s>%s</%1$s>',
			$this->_getRootNodeName(),
			$this->_serializeRootAttributes(),
			$this->_serializeContents()
		);
		$canonicalXml = $this->_getHelper()->getPayloadAsDoc($xmlString)->C14N();
		$this->_schemaValidate($canonicalXml);
		return $canonicalXml;
	}

	/**
	 * Fill out this payload object with data from the supplied string.
	 *
	 * @throws EbayEnterprise_RiskInsight_Model_Exception_Invalid_Payload_Exception
	 * @param  string $serializedPayload
	 * @return $this
	 */
	public function deserialize($serializedPayload)
	{
		// make sure we received a valid serialization of the payload.
		$this->_schemaValidate($serializedPayload);
		parent::deserialize($serializedPayload);
		return $this;
	}

	/**
	 * Validate the serialized data via the schema validator.
	 *
	 * @param  string $serializedData
	 * @return $this
	 */
	protected function _schemaValidate($serializedData)
	{
		$this->_getXsdValidator()->validate($serializedData, $this->_getSchemaFile());
		return $this;
	}

	/**
	 * Name, value pairs of root attributes
	 *
	 * @return array
	 */
	protected function _getRootAttributes()
	{
		return array(
			'xmlns' => $this->_getXmlNamespace(),
		);
	}

	/**
	 * Return the schema file path.
	 *
	 * @return string
	 */
	abstract protected function _getSchemaFile();

	/**
	 * Get path to the shared schema directory.
	 *
	 * @return string
	 */
	protected function _getSchemaDir()
	{
		return Mage::getModuleDir('', $this->_getModuleNameByClass(__CLASS__)) . DS . 'xsd' . DS;
	}

	/**
	 * @param  string $className
	 * @return string
	 */
	protected function _getModuleNameByClass($className)
	{
		$data = explode('_', $className);
		$size = count($data);
		return ($size > 1) ? $data[0] . '_' . $data[1] : $className;
	}
}
