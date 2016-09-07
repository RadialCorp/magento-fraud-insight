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

/**
 * @codeCoverageIgnore
 */
class Radial_FraudInsight_Block_System_Config_Testapiconnection
	extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	const API_VALIDATE_CONTROLLER = '*/fraud_insight_system_config_validate/validateapi';

	/** @var string */
	protected $_template = 'radial_fraudinsight/system/config/testapiconnection.phtml';

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
	 * @return Radial_FraudInsight_Helper_Data
	 */
	protected function _getHelper()
	{
		return Mage::helper('radial_fraudinsight');
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
