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

/**
 * @codeCoverageIgnore
 */
class EbayEnterprise_RiskInsight_Model_Config
	implements EbayEnterprise_RiskInsight_Model_IConfig
{
	/** @var string */
	protected $_apiKey;
	/** @var string */
	protected $_host;
	/** @var string */
	protected $_storeId;
	/** @var string */
	protected $_action = '_post';
	/** @var string */
	protected $_contentType = 'text/xml';
	/** @var EbayEnterprise_RiskInsight_Model_IPayload */
	protected $_request;
	/** @var EbayEnterprise_RiskInsight_Model_IPayload */
	protected $_response;
	/** @var EbayEnterprise_RiskInsight_Model_IPayload */
	protected $_error;

	/**
	 * @param array $initParams Must have this key:
	 *                          - 'api_key' => string
	 *                          - 'host' => string
	 *                          - 'store_id' => string
	 *                          - 'request' => EbayEnterprise_RiskInsight_Model_IPayload
	 *                          - 'response' => EbayEnterprise_RiskInsight_Model_IPayload
	 *                          - 'error' => EbayEnterprise_RiskInsight_Model_IPayload
	 */
	public function __construct(array $initParams=array())
	{
		list(
			$this->_apiKey,
			$this->_host,
			$this->_storeId,
			$this->_request,
			$this->_response,
			$this->_error
		) = $this->_checkTypes(
			$this->_nullCoalesce($initParams, 'api_key', null),
			$this->_nullCoalesce($initParams, 'host', null),
			$this->_nullCoalesce($initParams, 'store_id', null),
			$this->_nullCoalesce($initParams, 'request', Mage::getModel('ebayenterprise_riskinsight/request')),
			$this->_nullCoalesce($initParams, 'response', Mage::getModel('ebayenterprise_riskinsight/response')),
			$this->_nullCoalesce($initParams, 'error', Mage::getModel('ebayenterprise_riskinsight/error'))
		);
	}

	/**
	 * Type hinting for self::__construct $initParams
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @return array
	 */
	protected function _checkTypes(
		$apiKey,
		$host,
		$storeId,
		EbayEnterprise_RiskInsight_Model_IPayload $request,
		EbayEnterprise_RiskInsight_Model_IPayload $response,
		EbayEnterprise_RiskInsight_Model_IPayload $error
	) {
		return array($apiKey, $host, $storeId, $request, $response, $error);
	}

	/**
	 * Return the value at field in array if it exists. Otherwise, use the default value.
	 *
	 * @param  array
	 * @param  string | int $field Valid array key
	 * @param  mixed
	 * @return mixed
	 */
	protected function _nullCoalesce(array $arr, $field, $default)
	{
		return isset($arr[$field]) ? $arr[$field] : $default;
	}

	public function getApiKey()
	{
		return $this->_apiKey;
	}

	public function getEndpoint()
	{
		return $this->_host;
	}

	public function getHttpMethod()
	{
		return $this->_action;
	}

	public function getContentType()
	{
		return $this->_contentType;
	}

	public function getRequest()
	{
		return $this->_request;
	}

	public function setRequest(EbayEnterprise_RiskInsight_Model_IPayload $request)
	{
		$this->_request = $request;
		return $this;
	}

	public function getResponse()
	{
		return $this->_response;
	}

	public function setResponse(EbayEnterprise_RiskInsight_Model_IPayload $response)
	{
		$this->_response = $response;
		return $this;
	}

	public function getError()
	{
		return $this->_error;
	}

	public function setError(EbayEnterprise_RiskInsight_Model_IPayload $error)
	{
		$this->_error = $error;
		return $this;
	}
}
