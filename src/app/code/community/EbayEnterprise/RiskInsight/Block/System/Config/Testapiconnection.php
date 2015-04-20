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
class EbayEnterprise_RiskInsight_Block_System_Config_Testapiconnection
	extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	const API_VALIDATE_CONTROLLER = '*/risk_insight_system_config_validate/validateapi';

	/** @var string */
	protected $_template = 'ebayenterprise_riskinsight/system/config/testapiconnection.phtml';

	/**
	 * Unset some non-related element parameters
	 *
	 * @param  Varien_Data_Form_Element_Abstract
	 * @return string
	 */
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
		return parent::render($element);
	}

	/**
	 * @return EbayEnterprise_RiskInsight_Helper_Data
	 */
	protected function _getHelper()
	{
		return Mage::helper('ebayenterprise_riskinsight');
	}

	/**
	 * Get the button and scripts contents
	 *
	 * @param  Varien_Data_Form_Element_Abstract
	 * @return string
	 */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$this->addData($this->_getElementDisplayData($element));
		return $this->_toHtml();
	}

	/**
	 * @param  Varien_Data_Form_Element_Abstract
	 * @return array
	 */
	protected function _getElementDisplayData(Varien_Data_Form_Element_Abstract $element)
	{
		$originalData = $element->getOriginalData();
		return array(
			'button_label' => $this->_getHelper()->__($this->escapeHtml($originalData['button_label'])),
			'html_id' => $element->getHtmlId(),
			'ajax_url' => $this->getUrl(static::API_VALIDATE_CONTROLLER, array('_current' => true))
		);
	}
}
