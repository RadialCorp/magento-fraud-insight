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

class EbayEnterprise_RiskInsight_Risk_Insight_System_Config_ValidateController
	extends Mage_Adminhtml_Controller_Action
{
	const HOSTNAME_PARAM = 'hostname';
	const HOSTNAME_USE_DEFAULT_PARAM = 'hostname_use_default';
	const API_KEY_PARAM = 'api_key';
	const API_KEY_USE_DEFAULT_PARAM = 'api_key_use_default';
	const STORE_ID_PARAM = 'store_id';
	const STORE_ID_USE_DEFAULT_PARAM = 'store_id_use_default';

	/** @var EbayEnterprise_RiskInsight_Helper_Validator */
	protected $_validatorHelper;

	/**
	 * @param Zend_Controller_Request_Abstract
	 * @param Zend_Controller_Response_Abstract
	 * @param array $initParams May contain:
	 *                          - 'validator_helper' => EbayEnterprise_RiskInsight_Helper_Validator
	 */
	public function __construct(
		Zend_Controller_Request_Abstract $request,
		Zend_Controller_Response_Abstract $response,
		array $initParams=array()
	)
	{
		parent::__construct($request, $response, $initParams);
		list($this->_validatorHelper) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'validator_helper', Mage::helper('ebayenterprise_riskinsight/validator'))
		);
	}

	/**
	 * Type checks for __construct's $initParams
	 *
	 * @param  EbayEnterprise_RiskInsight_Helper_Validator
	 * @return array
	 */
	protected function _checkTypes(
		EbayEnterprise_RiskInsight_Helper_Validator $validatorHelper
	)
	{
		return array($validatorHelper);
	}

	/**
	 * Get the value form the array if set, else the default value.
	 *
	 * @param  array
	 * @param  string|int
	 * @param  mixed
	 * @return mixed
	 */
	protected function _nullCoalesce(array $arr, $field, $default)
	{
		return isset($arr[$field]) ? $arr[$field] : $default;
	}

	/**
	 * Validate the API configuration by making a test risk insight request
	 * and ensuring the response that is returned is valid.
	 *
	 * @return self
	 */
	public function validateapiAction()
	{
		$request = $this->getRequest();
		$hostname = $this->_validatorHelper->getParamOrFallbackValue(
			$request,
			self::HOSTNAME_PARAM,
			self::HOSTNAME_USE_DEFAULT_PARAM,
			EbayEnterprise_RiskInsight_Helper_Config::API_HOSTNAME
		);
		$storeId = $this->_validatorHelper->getParamOrFallbackValue(
			$request,
			self::STORE_ID_PARAM,
			self::STORE_ID_USE_DEFAULT_PARAM,
			EbayEnterprise_RiskInsight_Helper_Config::STORE_ID
		);
		$apiKey = $this->_validatorHelper->getEncryptedParamOrFallbackValue(
			$request,
			self::API_KEY_PARAM,
			self::API_KEY_USE_DEFAULT_PARAM,
			EbayEnterprise_RiskInsight_Helper_Config::API_KEY
		);

		$this->getResponse()->setHeader('Content-Type', 'text/json')
			->setBody(json_encode($this->_validatorHelper->testApiConnection($storeId, $apiKey, $hostname)));
		return $this;
	}
}
