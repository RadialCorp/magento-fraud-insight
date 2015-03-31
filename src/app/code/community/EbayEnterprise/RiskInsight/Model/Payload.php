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

abstract class EbayEnterprise_RiskInsight_Model_Payload
	implements EbayEnterprise_RiskInsight_Model_IPayload
{
	/** @var EbayEnterprise_RiskInsight_Model_Xsd_Validator */
	protected $_schemaValidator;
	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helper;
	/** @var array $_extractionPaths - XPath expressions to extract required data from the serialized payload (XML) */
	protected $_extractionPaths = array();
	/** @var array */
	protected $_optionalExtractionPaths = array();
	/** @var array */
	protected $_dateTimeExtractionPaths = array();
	/** @var array $_booleanExtractionPaths - property/XPath pairs that take boolean values */
	protected $_booleanExtractionPaths = array();
	/**
	 * @var array $_subpayloadExtractionPaths - property/XPath pairs. if property is a payload, first node matched
	 *            will be deserialized by that payload
	 */
	protected $_subpayloadExtractionPaths = array();

	/**
	 * Fill out this payload object with data from the supplied string.
	 *
	 * @throws EbayEnterprise_RiskInsight_Model_Exception_Invalid_Payload_Exception
	 * @param  string
	 * @return $this
	 */
	public function deserialize($serializedPayload)
	{
		$xpath = $this->_getHelper()->getPayloadAsXPath($serializedPayload, $this->_getXmlNamespace());
		$this->_deserializeExtractionPaths($xpath)
			->_deserializeOptionalExtractionPaths($xpath)
			->_deserializeBooleanExtractionPaths($xpath)
			->_deserializeLineItems($serializedPayload)
			->_deserializeSubpayloadExtractionPaths($xpath)
			->_deserializeDateTimeExtractionPaths($xpath)
			->_deserializeExtra($serializedPayload);
		return $this;
	}

	/**
	 * @param  DOMXPath
	 * @return self
	 */
	protected function _deserializeExtractionPaths(DOMXPath $xpath)
	{
		foreach ($this->_extractionPaths as $setter => $path) {
			$this->$setter($xpath->evaluate($path));
		}
		return $this;
	}

	/**
	 * When optional nodes are not included in the serialized data,
	 * they should not be set in the payload. Fortunately, these
	 * are all string values so no additional type conversion is necessary.
	 *
	 * @param  DOMXPath
	 * @return self
	 */
	protected function _deserializeOptionalExtractionPaths(DOMXPath $xpath)
	{
		foreach ($this->_optionalExtractionPaths as $setter => $path) {
			$foundNode = $xpath->query($path)->item(0);
			if ($foundNode) {
				$this->$setter($foundNode->nodeValue);
			}
		}
		return $this;
	}

	/**
	 * boolean values have to be handled specially
	 *
	 * @param  DOMXPath
	 * @return self
	 */
	protected function _deserializeBooleanExtractionPaths(DOMXPath $xpath)
	{
		foreach ($this->_booleanExtractionPaths as $setter => $path) {
			$value = $xpath->evaluate($path);
			$this->$setter($this->_getHelper()->convertStringToBoolean($value));
		}
		return $this;
	}

	/**
	 * @param  DOMXPath
	 * @return self
	 */
	protected function _deserializeSubpayloadExtractionPaths(DOMXPath $xpath)
	{
		foreach ($this->_subpayloadExtractionPaths as $setter => $path) {
			$foundNode = $xpath->query($path)->item(0);
			$getter = 'g' . substr($setter, 1);
			if ($foundNode && $this->$getter() instanceof EbayEnterprise_RiskInsight_Model_IPayload) {
				$this->$getter()->deserialize($foundNode->C14N());
			}
		}
		return $this;
	}

	/**
	 * Ensure any date time string is instantiate
	 *
	 * @param  DOMXPath
	 * @return self
	 */
	protected function _deserializeDateTimeExtractionPaths(DOMXPath $xpath)
	{
		foreach ($this->_dateTimeExtractionPaths as $setter => $path) {
			$value = $xpath->evaluate($path);
			if ($value) {
				$this->$setter(new DateTime($value));
			}
		}
		return $this;
	}

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
		return $this->_canSerialize() ? $canonicalXml : '';
	}

	protected function _canSerialize()
	{
		return true;
	}

	/**
	 * Stash the xsd validator in the class property '_schemaValidator'
	 *
	 * @return EbayEnterprise_RiskInsight_Model_Xsd_Validator
	 */
	protected function _getXsdValidator()
	{
		if (!$this->_schemaValidator) {
			$this->_schemaValidator = Mage::getModel('ebayenterprise_riskinsight/xsd_validator');
		}
		return $this->_schemaValidator;
	}

	/**
	 * Stash the helper class in the class property '_helper'
	 *
	 * @return EbayEnterprise_RiskInsight_Helper_Data
	 */
	protected function _getHelper()
	{
		if (!$this->_helper) {
			$this->_helper = Mage::helper('ebayenterprise_riskinsight');
		}
		return $this->_helper;
	}

	/**
	 * Additional deserialization of the payload data. May contain any
	 * special case deserialization that cannot be expressed by the supported
	 * deserialization paths. Default implementation is a no-op. Expected to
	 * be overridden by payloads that need it.
	 *
	 * @param  string
	 * @return self
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	protected function _deserializeExtra($serializedPayload)
	{
		return $this;
	}

	/**
	 * convert line item substrings into line item objects
	 *
	 * @param  string
	 * @return self
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	protected function _deserializeLineItems($serializedPayload)
	{
		return $this;
	}

	/**
	 * Build a new EbayEnterprise_RiskInsight_Model_IPayload for the given interface.
	 *
	 * @param  string
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _buildPayloadForModel($model)
	{
		return Mage::getModel($model);
	}

	/**
	 * Return the name of the xml root node.
	 *
	 * @return string
	 */
	abstract protected function _getRootNodeName();

	/**
	 * Serialize Root Attributes
	 *
	 * @return string
	 */
	protected function _serializeRootAttributes()
	{
		$rootAttributes = $this->_getRootAttributes();
		$qualifyAttributes = function ($name) use ($rootAttributes) {
			return sprintf('%s="%s"', $name, $rootAttributes[$name]);
		};
		$qualifiedAttributes = array_map($qualifyAttributes, array_keys($rootAttributes));
		return implode(' ', $qualifiedAttributes);
	}

	/**
	 * XML Namespace of the document.
	 *
	 * @return string
	 */
	abstract protected function _getXmlNamespace();

	/**
	 * Name, value pairs of root attributes
	 *
	 * @return array
	 */
	protected function _getRootAttributes()
	{
		return array();
	}

	/**
	 * Serialize the various parts of the payload into XML strings and concatenate them together.
	 *
	 * @return string
	 */
	abstract protected function _serializeContents();

	/**
	 * Serialize the value as an xml element with the given node name.
	 *
	 * @param  string
	 * @param  mixed
	 * @return string
	 */
	protected function _serializeNode($nodeName, $value)
	{
		return sprintf('<%s>%s</%1$s>', $nodeName, $this->_getHelper()->escapeHtml($value));
	}

	protected function _serializeAmountNode($nodeName, $amount)
	{
		return "<$nodeName>{$this->_getHelper()->formatAmount($amount)}</$nodeName>";
	}

	/**
	 * Serialize the value as an xml element with the given node name. When
	 * given an empty value, returns an empty string instead of an empty
	 * element.
	 *
	 * @param  string
	 * @param  mixed
	 * @return string
	 */
	protected function _serializeOptionalValue($nodeName, $value)
	{
		return (!is_null($value) && $value !== '') ? $this->_serializeNode($nodeName, $value) : '';
	}

	/**
	 * Serialize the currency amount as an XML node with the provided name.
	 * When the amount is not set, returns an empty string.
	 *
	 * @param  string
	 * @param  float
	 * @return string
	 */
	protected function _serializeOptionalAmount($nodeName, $amount)
	{
		return (!is_null($amount) && !is_nan($amount)) ? "<$nodeName>{$this->_getHelper()->formatAmount($amount)}</$nodeName>" : '';
	}

	protected function _serializeOptionalNumber($nodeName, $number)
	{
		return (!is_null($number) && !is_nan($number)) ? "<$nodeName>{$number}</$nodeName>" : '';
	}

	/**
	 * Serialize an optional date time value. When a DateTime value is given,
	 * serialize the DateTime object as an XML node containing the DateTime
	 * formatted with the given format. When no DateTime is given, return
	 * an empty string.
	 *
	 * @param  string
	 * @param  string
	 * @param  DateTime
	 * @return string
	 */
	protected function _serializeOptionalDateValue($nodeName, $format, DateTime $date = null)
	{
		return $date ? "<$nodeName>{$date->format($format)}</$nodeName>" : '';
	}
}
