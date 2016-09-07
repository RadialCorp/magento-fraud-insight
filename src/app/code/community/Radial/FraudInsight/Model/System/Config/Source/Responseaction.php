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
class Radial_FraudInsight_Model_System_Config_Source_Responseaction
	implements Radial_FraudInsight_Model_System_Config_Source_IResponseaction
{
	public function toOptionArray()
	{
		$options = array(static::PROCESS, static::HOLD_FOR_REVIEW, static::CANCEL);
		$optionValues = array(array('value' => '', 'label' => ''));
		foreach ($options as $option) {
			$optionValues[] = array('value' => $option, 'label' => $option);
		}
		return $optionValues;
	}
}
