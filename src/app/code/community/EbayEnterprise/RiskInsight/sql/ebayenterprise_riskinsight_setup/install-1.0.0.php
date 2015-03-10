<?php
/**
 * Copyright (c) 2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the eBay Enterprise
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf
 *
 * @copyright   Copyright (c) 2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://www.ebayenterprise.com/files/pdf/Magento_Connect_Extensions_EULA_050714.pdf  eBay Enterprise Magento Extensions End User License Agreement
 *
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
/** @var Varien_Db_Adapter_Interface $conn */
$installer = $this;
$installer->startSetup();
$conn->dropTable($installer->getTable('ebayenterprise_riskinsight/risk_insight'));
$table = $conn
	->newTable($installer->getTable('ebayenterprise_riskinsight/risk_insight'))
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
	 ), 'Risk Insight Primary Key')
	->addColumn('order_increment_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
		'nullable' => false,
	), 'Order Increment ID')
	->addColumn('http_headers', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' => true,
	), 'HTTP Headers Information')
	->addColumn('response_reason_code', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
		'nullable' => true,
	), 'Response Reason Code')
	->addColumn('response_reason_code_description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable' => true,
	), 'Response Reason Code Description')
	->addColumn('is_request_sent', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
	), 'Flag to indicate ucp request has been sent')
	->addIndex(
		$installer->getIdxName('ebayenterprise_riskinsight/risk_insight', array('order_increment_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
		array('order_increment_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
	);

$conn->createTable($table);
$installer->endSetup();
