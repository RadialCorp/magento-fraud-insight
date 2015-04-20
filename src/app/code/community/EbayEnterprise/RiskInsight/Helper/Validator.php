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

class EbayEnterprise_RiskInsight_Helper_Validator
{
	const RISK_INSIGHT_REQUEST = <<<EOF
<?xml version="1.0" encoding="utf-8"?>
<RiskInsightRequest xmlns="http://schema.gsicommerce.com/risk/insight/1.0/">
	<PrimaryLangId>en</PrimaryLangId>
	<Order>
		<OrderId>100000000</OrderId>
		<OrderSource>WEBSTORE</OrderSource>
		<OrderDate>2015-04-20T15:53:15+00:00</OrderDate>
		<StoreId>MAGTEST</StoreId>
		<ShippingList>
			<Shipment ShipmentId="913">
				<PersonName>
					<LastName>Scenario</LastName>
					<FirstName>One</FirstName>
				</PersonName>
				<Telephone>
					<Number>555-555-5555</Number>
				</Telephone>
				<Address>
					<Line1>630 Allendale Rd</Line1>
					<City>KING OF PRUSSIA</City>
					<PostalCode>19406-1342</PostalCode>
					<MainDivisionCode>PA</MainDivisionCode>
					<CountryCode>US</CountryCode>
				</Address>
				<ShippingMethod>ups_GND</ShippingMethod>
			</Shipment>
		</ShippingList>
		<LineItems>
			<LineItem LineItemId="878" ShipmentId="913">
				<ProductId>hdd005</ProductId>
				<Description>Fragrance Diffuser Reeds</Description>
				<UnitCost>86.42</UnitCost>
				<UnitCurrencyCode>USD</UnitCurrencyCode>
				<Quantity>1</Quantity>
				<Category>Root Catalog-&gt;Default Category-&gt;Home &amp; Decor-&gt;Decorative Accents</Category>
			</LineItem>
		</LineItems>
		<FormOfPayments>
			<FormOfPayment>
				<PaymentCard>
					<CardHolderName>Scenario One</CardHolderName>
					<PaymentAccountUniqueId isToken="true">aL+zlvNa84dvxQlmWz3COgkwqrE=</PaymentAccountUniqueId>
					<PaymentAccountBin>411111</PaymentAccountBin>
					<ExpireDate>2023-09</ExpireDate>
					<CardType>VC</CardType>
				</PaymentCard>
				<PersonName>
					<LastName>Scenario</LastName>
					<FirstName>One</FirstName>
				</PersonName>
				<Email>EMAIL.IP.CC@EBAY.COM</Email>
				<Telephone>
					<Number>555-555-5555</Number>
				</Telephone>
				<Address>
					<Line1>630 Allendale Rd</Line1>
					<City>KING OF PRUSSIA</City>
					<PostalCode>19406-1342</PostalCode>
					<MainDivisionCode>PA</MainDivisionCode>
					<CountryCode>US</CountryCode>
				</Address>
				<PaymentTransactionDate>2015-04-20T15:52:33+00:00</PaymentTransactionDate>
				<CurrencyCode>USD</CurrencyCode>
			</FormOfPayment>
		</FormOfPayments>
		<TotalCost>
			<CostTotals>
				<CurrencyCode>USD</CurrencyCode>
				<AmountBeforeTax>86.42</AmountBeforeTax>
				<AmountAfterTax>99.96</AmountAfterTax>
			</CostTotals>
		</TotalCost>
		<DeviceInfo>
			<DeviceIP>172.17.42.1</DeviceIP>
			<HttpHeaders>
				<HttpHeader name="Authorization"/>
				<HttpHeader name="Host">digi-ucp.com</HttpHeader>
				<HttpHeader name="User-Agent">Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:37.0) Gecko/20100101 Firefox/37.0</HttpHeader>
				<HttpHeader name="Accept">text/javascript, text/html, application/xml, text/xml, */*</HttpHeader>
				<HttpHeader name="Accept-Language">en-US,en;q=0.5</HttpHeader>
				<HttpHeader name="Accept-Encoding">gzip, deflate</HttpHeader>
				<HttpHeader name="X-Requested-With">XMLHttpRequest</HttpHeader>
				<HttpHeader name="X-Prototype-Version">1.7</HttpHeader>
				<HttpHeader name="Referer">http://digi-ucp.com/checkout/onepage/</HttpHeader>
				<HttpHeader name="Cookie">adminhtml=d8f1f4f496267a9259e4304e893eecba; frontend=1a481226b2df9dcbcb7ad1257782f99a</HttpHeader>
				<HttpHeader name="Connection">keep-alive</HttpHeader>
				<HttpHeader name="Pragma">no-cache</HttpHeader>
				<HttpHeader name="Cache-Control">no-cache</HttpHeader>
			</HttpHeaders>
		</DeviceInfo>
	</Order>
</RiskInsightRequest>
EOF;
	const INVALID_API_SETTINGS_ERROR_MESSAGE = 'Missing API settings: %s';
	const API_CONNECTION_SUCCESS_MESSAGE = 'API connection successful.';
	const API_CONNECTION_FAILURE_MESSAGE = 'API connection failed.';

	/** @var EbayEnterprise_RiskInsight_Helper_Data */
	protected $_helper;

	public function __construct()
	{
		$this->_helper = Mage::helper('ebayenterprise_riskinsight');
	}

	/**
	 * Get new empty request payload
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _getNewEmptyRequest()
	{
		return Mage::getModel('ebayenterprise_riskinsight/request');
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _loadRequest()
	{
		$request = $this->_getNewEmptyRequest();
		$request->deserialize(static::RISK_INSIGHT_REQUEST);
		return $request;
	}

	/**
	 * Get new empty response payload
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IPayload
	 */
	protected function _getNewEmptyResponse()
	{
		return Mage::getModel('ebayenterprise_riskinsight/response');
	}

	/**
	 * Get new API config object.
	 *
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  EbayEnterprise_RiskInsight_Model_IPayload
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return EbayEnterprise_RiskInsight_Model_IConfig
	 */
	protected function _setupApiConfig(
		EbayEnterprise_RiskInsight_Model_IPayload $request,
		EbayEnterprise_RiskInsight_Model_IPayload $response,
		$apiKey,
		$apiHostname,
		$storeId
	)
	{
		return Mage::getModel('ebayenterprise_riskinsight/config', array(
			'api_key' => $apiKey,
			'host' => $apiHostname,
			'store_id' => $storeId,
			'request' => $request,
			'response' => $response,
		));
	}

	/**
	 * Get new API object.
	 *
	 * @return EbayEnterprise_RiskInsight_Model_IApi
	 */
	protected function _getApi(EbayEnterprise_RiskInsight_Model_IConfig $config)
	{
		return Mage::getModel('ebayenterprise_riskinsight/api', $config);
	}

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IApi
	 * @return EbayEnterprise_RiskInsight_Model_IPayload | null
	 */
	protected function _sendRequest(EbayEnterprise_RiskInsight_Model_IApi $api)
	{
		$api->send();
		$response = $api->getResponseBody();
		return $response;
	}

	/**
	 * Test required API settings. If required API settings are valid, then
	 * continue to test API Connection by sending risk insight request payload.
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return array
	 */
	public function testApiConnection($storeId, $apiKey, $hostname)
	{
		$settingsResponse = $this->_testSettings($storeId, $apiKey, $hostname);
		return !empty($settingsResponse)
			? $settingsResponse : $this->_testConnection($storeId, $apiKey, $hostname);
	}

	/**
	 * Test the API Settings that are required to make a connection.
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return array
	 */
	protected function _testSettings($storeId, $apiKey, $hostname)
	{
		try {
			$this->_validateApiSettings($storeId, $apiKey, $hostname);
		} catch (EbayEnterprise_RiskInsight_Model_Exception_Api_Configuration_Exception $e) {
			return array('message' => $e->getMessage(), 'success' => false);
		}
		return array();
	}

	/**
	 * Test the API connection to host server.
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return array
	 */
	protected function _testConnection($storeId, $apiKey, $hostname)
	{
		$apiConfig = $this->_setupApiConfig($this->_loadRequest(), $this->_getNewEmptyResponse(), $apiKey, $hostname, $storeId);
		try{
			$response = $this->_sendRequest($this->_getApi($apiConfig));
		} catch (Exception $e) {
			$response = false;
			$this->_handleConnectionError($e);
		}
		return $response
			? array('message' => $this->_helper->__(static::API_CONNECTION_SUCCESS_MESSAGE), 'success' => true)
			: array('message' => $this->_helper->__(static::API_CONNECTION_FAILURE_MESSAGE), 'success' => false);
	}

	/**
	 * @param  Exception
	 * @return self
	 */
	protected function _handleConnectionError(Exception $e)
	{
		$logMessage = sprintf('[%s] Api Connection Error: %s', __CLASS__, $e->getMessage());
		Mage::log($logMessage, Zend_Log::WARN);
		Mage::logException($e);
		return $this;
	}

	/**
	 * Validate the store id, API key and hostname settings, ensuring that
	 * none are empty.
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return self
	 * @throws EbayEnterprise_RiskInsight_Model_Exception_Api_Configuration_Exception If any settings are empty
	 */
	protected function _validateApiSettings($storeId, $apiKey, $hostname)
	{
		$invalidSettings = [];
		if ($storeId === '') {
			$invalidSettings[] = 'Store Id';
		}
		if ($apiKey === '') {
			$invalidSettings[] = 'API Key';
		}
		if ($hostname === '') {
			$invalidSettings[] = 'API Hostname';
		}
		if (!empty($invalidSettings)) {
			throw Mage::exception(
				'EbayEnterprise_RiskInsight_Model_Exception_Api_Configuration',
				$this->_helper->__(self::INVALID_API_SETTINGS_ERROR_MESSAGE, implode(', ', $invalidSettings))
			);
		}
		return $this;
	}

	/**
	 * Get the param from the request if included in the request in the unencrypted
	 * state (not replaced by ******). When not included, get the value from
	 * config, decrypting the value if necessary.
	 *
	 * @param Zend_Controller_Request_Abstract
	 * @param string $param Name of the param that may contain the value
	 * @param string $useDefaultParam  Name of the param indicating if the value should fallback
	 * @param string $configPath
	 * @return string
	 */
	public function getEncryptedParamOrFallbackValue(Zend_Controller_Request_Abstract $request, $param, $useDefaultParam, $configPath)
	{
		$key = $request->getParam($param);
		$useDefault = $request->getParam($useDefaultParam);
		if (is_null($key) || preg_match('/^\*+$/', $key) || $useDefault) {
			$configSource = $this->getConfigSource($request, $useDefault);
			$key = $configSource->getConfig($configPath);
			// As there is a config/default node with a backend_model set for this
			// config value, Mage_Core_Model_Store->getConfig will auto-decrypt the
			// value when the config source is a Mage_Core_Model_Store. When the
			// source is a Mage_Core_Model_Website, this doesn't happen automatically
			// so the config value needs to be manually decrypted.
			if ($configSource instanceof Mage_Core_Model_Website) {
				$key = Mage::helper('core')->decrypt($key);
			}
		}
		return trim($key);
	}

	/**
	 * Get the value from the request or via the config fallback.
	 *
	 * @param Zend_Controller_Request_Abstract
	 * @param string $param Name of the param that may contain the value
	 * @param string $useDefaultParam  Name of the param indicating if the value should fallback
	 * @param string $configPath Core config registry key to get a fallback value for
	 * @return string
	 */
	public function getParamOrFallbackValue(Zend_Controller_Request_Abstract $request, $param, $useDefaultParam, $configPath)
	{
		$paramValue = $request->getParam($param);
		$useFallback = $request->getParam($useDefaultParam);

		if (is_null($paramValue) || $useFallback) {
			return $this->getConfigSource($request, $useFallback)
				->getConfig($configPath);
		}
		return trim($paramValue);
	}

	/**
	 * @return Mage_Core_Model_App
	 */
	protected function _getApp()
	{
		return Mage::app();
	}

	/**
	 * Get the source of configuration for the request. Should use the store
	 * or website specified in the request params. If neither is present, should
	 * use the default store.
	 *
	 * @param Zend_Controller_Request_Abstract
	 * @param bool $useFallback Should the config value fallback to parent value
	 * @return Mage_Core_Model_Store|Mage_Core_Model_Website
	 */
	public function getConfigSource(Zend_Controller_Request_Abstract $request, $useFallback=false)
	{
		$store = $request->getParam('store');
		$website = $request->getParam('website');
		$app = $this->_getApp();

		if ($store) {
			$storeObj = $app->getStore($store);
			// specific store view should fall back to website store view is in
			return $useFallback ? $storeObj->getWebsite() : $storeObj;
		}
		if ($website) {
			$websiteObj = $app->getWebsite($website);
			// website should fall back to default store
			return $useFallback ? $app->getStore(null) : $websiteObj;
		}
		// default to default store
		return $app->getStore(null);
	}
}
