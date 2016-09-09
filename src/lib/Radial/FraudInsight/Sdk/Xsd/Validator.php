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

class Radial_FraudInsight_Sdk_Xsd_Validator
	implements Radial_FraudInsight_Sdk_Schema_IValidator
{
	/**
	 * Validate the serialized string as XML against a provided XSD schema.
	 *
	 * @param  string
	 * @param  string $schemaFile Path to XSD file
	 * @return self
	 * @throws Radial_FraudInsight_Sdk_Exception_Invalid_Payload_Exception If the XML does not pass XSD schema validation.
	 */
	public function validate($xmlString, $schemaFile=null)
	{
		$doc = $this->_loadXmlDoc($xmlString);
		$originalUseErrors = $this->_setupValidationErrorHandling();
		$isValid = $doc->schemaValidate($schemaFile);
		$errors = $this->_retrieveErrors();
		$this->_teardownValidationErrorHandling($originalUseErrors);
		if (!$isValid) {
			throw Mage::exception('Radial_FraudInsight_Sdk_Exception_Invalid_Payload', $this->_formatErrors($errors));
		}
		return $this;
	}

	/**
	 * Load the XML string into a DOMDocument
	 *
	 * @param  string
	 * @return DOMDocument
	 */
	protected function _loadXmlDoc($xmlString)
	{
		$d = new DOMDocument();
		$d->loadXML($xmlString);
		return $d;
	}

	/**
	 * Set libxml to use user defined error handling. Return the previous
	 * setting to be reset after schema validation is complete.
	 *
	 * @return bool
	 */
	protected function _setupValidationErrorHandling()
	{
		return libxml_use_internal_errors(true);
	}

	/**
	 * Retrieve errors encountered while validating the XML against the schema.
	 *
	 * @return array
	 */
	protected function _retrieveErrors()
	{
		return libxml_get_errors();
	}

	/**
	 * Reset libxml error handling.
	 *
	 * @param  bool $oldUseErrors Value to reset
	 * @return self
	 */
	protected function _teardownValidationErrorHandling($oldUseErrors)
	{
		libxml_use_internal_errors($oldUseErrors);
		return $this;
	}

	/**
	 * Format the array of validation errors.
	 *
	 * @param  array
	 * @return string
	 */
	protected function _formatErrors(array $libxmlErrors=array())
	{
		return 'XSD validation failed with following messages:' . PHP_EOL . implode('', array_map(
			function (\libXMLError $xmlError) {
				return sprintf(
					'[%s:%d] %s',
					$xmlError->file,
					$xmlError->line,
					$xmlError->message
				);
			},
			$libxmlErrors
		));
	}
}
