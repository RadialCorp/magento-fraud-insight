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

interface Radial_FraudInsight_Sdk_IShipment extends Radial_FraudInsight_Sdk_IPayload
{
	const ROOT_NODE = 'Shipment';
	const XML_NS = 'http://schema.gsicommerce.com/risk/insight/1.0/';
	const PERSON_NAME_MODEL ='Radial_FraudInsight_Sdk_Person_Name';
	const TELEPHONE_MODEL ='Radial_FraudInsight_Sdk_Telephone';
	const ADDRESS_MODEL ='Radial_FraudInsight_Sdk_Address';

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
