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

class Radial_FraudInsight_Fraud_Insight_System_Config_ValidateController
	extends Mage_Adminhtml_Controller_Action
{
	const HOSTNAME_PARAM = 'hostname';
	const HOSTNAME_USE_DEFAULT_PARAM = 'hostname_use_default';
	const API_KEY_PARAM = 'api_key';
	const API_KEY_USE_DEFAULT_PARAM = 'api_key_use_default';
	const STORE_ID_PARAM = 'store_id';
	const STORE_ID_USE_DEFAULT_PARAM = 'store_id_use_default';

	/** @var Radial_FraudInsight_Helper_Validator */
	protected $_validatorHelper;

	/**
	 * @param Zend_Controller_Request_Abstract
	 * @param Zend_Controller_Response_Abstract
	 * @param array $initParams May contain:
	 *                          - 'validator_helper' => Radial_FraudInsight_Helper_Validator
	 */
	public function __construct(
		Zend_Controller_Request_Abstract $request,
		Zend_Controller_Response_Abstract $response,
		array $initParams=array()
	)
	{
		parent::__construct($request, $response, $initParams);
		list($this->_validatorHelper) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'validator_helper', Mage::helper('radial_fraudinsight/validator'))
		);
	}

	/**
	 * Type checks for __construct's $initParams
	 *
	 * @param  Radial_FraudInsight_Helper_Validator
	 * @return array
	 */
	protected function _checkTypes(
		Radial_FraudInsight_Helper_Validator $validatorHelper
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
			Radial_FraudInsight_Helper_Config::API_HOSTNAME
		);
		$storeId = $this->_validatorHelper->getParamOrFallbackValue(
			$request,
			self::STORE_ID_PARAM,
			self::STORE_ID_USE_DEFAULT_PARAM,
			Radial_FraudInsight_Helper_Config::STORE_ID
		);
		$apiKey = $this->_validatorHelper->getEncryptedParamOrFallbackValue(
			$request,
			self::API_KEY_PARAM,
			self::API_KEY_USE_DEFAULT_PARAM,
			Radial_FraudInsight_Helper_Config::API_KEY
		);

		$this->getResponse()->setHeader('Content-Type', 'text/json')
			->setBody(json_encode($this->_validatorHelper->testApiConnection($storeId, $apiKey, $hostname)));
		return $this;
	}

    /**
     * Check access (in the ACL) for current user.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/radial_fraudinsight');
    }
}
