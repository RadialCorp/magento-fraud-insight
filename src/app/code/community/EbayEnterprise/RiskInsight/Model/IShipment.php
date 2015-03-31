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

interface EbayEnterprise_RiskInsight_Model_IShipment extends EbayEnterprise_RiskInsight_Model_IPayload
{
	const ROOT_NODE = 'Shipment';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const PERSON_NAME_MODEL ='ebayenterprise_riskinsight/person_name';
	const TELEPHONE_MODEL ='ebayenterprise_riskinsight/telephone';
	const ADDRESS_MODEL ='ebayenterprise_riskinsight/address';

	/**
	 * @return string
	 */
	public function getShipmentId();

	/**
	 * @param  string
	 * @return self
	 */
	public function setShipmentId($shipmentId);

	/**
	 * @return EbayEnterprise_RiskInsight_Model_Person_IName
	 */
	public function getPersonName();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_Person_IName
	 * @return self
	 */
	public function setPersonName(EbayEnterprise_RiskInsight_Model_Person_IName $personName);

	/**
	 * A valid email address format.
	 *
	 * xsd restrictions: >= 1 characters
	 * @return string
	 */
	public function getEmail();

	/**
	 * @param  string
	 * @return self
	 */
	public function setEmail($email);

	/**
	 * @return EbayEnterprise_RiskInsight_Model_ITelephone
	 */
	public function getTelephone();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_ITelephone
	 * @return self
	 */
	public function setTelephone(EbayEnterprise_RiskInsight_Model_ITelephone $telephone);

	/**
	 * @return EbayEnterprise_RiskInsight_Model_IAddress
	 */
	public function getAddress();

	/**
	 * @param  EbayEnterprise_RiskInsight_Model_IAddress
	 * @return self
	 */
	public function setAddress(EbayEnterprise_RiskInsight_Model_IAddress $address);

	/**
	 * The method of shipment for the order.
	 * Sample Data: Standard_Ground,1DAY,2DAY,EXPRESS
	 *
	 * xsd restrictions: >= 1 characters
	 * @return string
	 */
	public function getShippingMethod();

	/**
	 * @param  string
	 * @return self
	 */
	public function setShippingMethod($shippingMethod);
}
