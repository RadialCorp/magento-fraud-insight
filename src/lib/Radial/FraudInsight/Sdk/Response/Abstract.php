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
abstract class Radial_FraudInsight_Sdk_Response_Abstract
	extends Radial_FraudInsight_Sdk_Payload_Top
	implements Radial_FraudInsight_Sdk_Response_IAbstract
{
	/** @var string */
	protected $_primaryLangId;
	/** @var string */
	protected $_orderId;
	/** @var string */
	protected $_storeId;

	/**
	 * @see Radial_FraudInsight_Sdk_Response_IAbstract::getPrimaryLangId()
	 */
	public function getPrimaryLangId()
	{
		return $this->_primaryLangId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Response_IAbstract::setPrimaryLangId()
	 */
	public function setPrimaryLangId($primaryLangId)
	{
		$this->_primaryLangId = $primaryLangId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Response_IAbstract::getOrderId()
	 */
	public function getOrderId()
	{
		return $this->_orderId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Response_IAbstract::setOrderId()
	 */
	public function setOrderId($orderId)
	{
		$this->_orderId = $orderId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Response_IAbstract::getStoreId()
	 */
	public function getStoreId()
	{
		return $this->_storeId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Response_IAbstract::setStoreId()
	 */
	public function setStoreId($storeId)
	{
		$this->_storeId = $storeId;
		return $this;
	}
}
