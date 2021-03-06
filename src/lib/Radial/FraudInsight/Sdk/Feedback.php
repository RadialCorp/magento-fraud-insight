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

class Radial_FraudInsight_Sdk_Feedback
	extends Radial_FraudInsight_Sdk_Payload_Top
	implements Radial_FraudInsight_Sdk_IFeedback
{
	/** @var string */
	protected $_primaryLangId;
	/** @var string */
	protected $_orderId;
	/** @var string */
	protected $_storeId;
	/** @var string */
	protected $_actionTaken;
	/** @var string */
	protected $_actionTakenDescription;
	/** @var bool */
	protected $_chargeBackCode;
	/** @var string */
	protected $_chargeBackFlagDescription;

	public function __construct(array $initParams=array())
	{
		parent::__construct($initParams);
		$this->_extractionPaths = array(
			'setOrderId' => 'string(x:OrderId)',
			'setStoreId' => 'string(x:StoreId)',
		);
		$this->_optionalExtractionPaths = array(
			'setPrimaryLangId' => 'x:PrimaryLangId',
			'setActionTaken' => 'x:ActionTaken',
			'setActionTakenDescription' => 'x:ActionTakenDescription',
			'setChargeBackCode' => 'x:ChargeBackCode',
			'setChargeBackFlagDescription' => 'x:ChargeBackFlagDescription',
		);
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::getPrimaryLangId()
	 */
	public function getPrimaryLangId()
	{
		return $this->_primaryLangId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::setPrimaryLangId()
	 */
	public function setPrimaryLangId($primaryLangId)
	{
		$this->_primaryLangId = $primaryLangId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::getOrderId()
	 */
	public function getOrderId()
	{
		return $this->_orderId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::setOrderId()
	 */
	public function setOrderId($orderId)
	{
		$this->_orderId = $orderId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::getStoreId()
	 */
	public function getStoreId()
	{
		return $this->_storeId;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::setStoreId()
	 */
	public function setStoreId($storeId)
	{
		$this->_storeId = $storeId;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::getActionTaken()
	 */
	public function getActionTaken()
	{
		return $this->_actionTaken;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::setActionTaken()
	 */
	public function setActionTaken($actionTaken)
	{
		$this->_actionTaken = $actionTaken;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::getActionTakenDescription()
	 */
	public function getActionTakenDescription()
	{
		return $this->_actionTakenDescription;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::setActionTakenDescription()
	 */
	public function setActionTakenDescription($actionTakenDescription)
	{
		$this->_actionTakenDescription = $actionTakenDescription;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::getChargeBackCode()
	 */
	public function getChargeBackCode()
	{
		return $this->_chargeBackCode;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::setChargeBackCode()
	 */
	public function setChargeBackCode($chargeBackCode)
	{
		$this->_chargeBackCode = $chargeBackCode;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::getChargeBackFlagDescription()
	 */
	public function getChargeBackFlagDescription()
	{
		return $this->_chargeBackFlagDescription;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_IFeedback::setChargeBackFlagDescription()
	 */
	public function setChargeBackFlagDescription($chargeBackFlagDescription)
	{
		$this->_chargeBackFlagDescription = $chargeBackFlagDescription;
		return $this;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload_Top::_getSchemaFile()
	 */
	protected function _getSchemaFile()
	{
		return $this->_getSchemaDir() . self::XSD;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getXmlNamespace()
	 */
	protected function _getXmlNamespace()
	{
		return static::XML_NS;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_getRootNodeName()
	 */
	protected function _getRootNodeName()
	{
		return static::ROOT_NODE;
	}

	/**
	 * @see Radial_FraudInsight_Sdk_Payload::_serializeContents()
	 */
	protected function _serializeContents()
	{
		return $this->_serializeOptionalValue('PrimaryLangId', $this->getPrimaryLangId())
			. $this->_serializeNode('OrderId', $this->getOrderId())
			. $this->_serializeNode('StoreId', $this->getStoreId())
			. $this->_serializeOptionalValue('ActionTaken', $this->getActionTaken())
			. $this->_serializeOptionalValue('ActionTakenDescription', $this->getActionTakenDescription())
			. $this->_serializeOptionalValue('ChargeBackCode', $this->getChargeBackCode())
			. $this->_serializeOptionalValue('ChargeBackFlagDescription', $this->getChargeBackFlagDescription());
	}
}
