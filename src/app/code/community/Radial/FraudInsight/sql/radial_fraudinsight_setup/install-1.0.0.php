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

/** @var Mage_Core_Model_Resource_Setup $installer */
/** @var Varien_Db_Adapter_Interface $conn */
$installer = $this;
$installer->startSetup();
$conn->dropTable($installer->getTable('radial_fraudinsight/fraud_insight'));
$table = $conn
	->newTable($installer->getTable('radial_fraudinsight/fraud_insight'))
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
	 ), 'Fraud Insight Primary Key')
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
	->addColumn('is_feedback_sent', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
	), 'Flag indicating feedback request was sent successfully')
	->addColumn('feedback_sent_attempt_count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
	), 'Count of how many fail attempt to successfully send feedback request')
	->addColumn('action_taken_acknowledgement', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
	), 'Feedback response action taken acknowledgement')
	->addColumn('charge_back_acknowledgement', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
	), 'Feedback response charge back acknowledgement')
	->addIndex(
		$installer->getIdxName('radial_fraudinsight/fraud_insight', array('order_increment_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
		array('order_increment_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
	);

$conn->createTable($table);

// Add Risk Order Statuses and States
$data = array(
	array('status' => 'risk_review', 'label' => 'Fraud Review', 'is_default' => 0, 'state' => Mage_Sales_Model_Order::STATE_HOLDED),
	array('status' => 'risk_canceled', 'label' => 'Fraud Canceled', 'is_default' => 0, 'state' => Mage_Sales_Model_Order::STATE_CANCELED),
);
$statusFields = array('status', 'label');
$stateFields = array('status', 'state', 'is_default');
$statusTbl = $installer->getTable('sales/order_status');
$stateTbl = $installer->getTable('sales/order_status_state');

foreach ($data as $datum) {
	$statusValues = array($datum['status'], $datum['label']);
	$conn->insertArray($statusTbl, $statusFields, array(array_combine($statusFields, $statusValues)));

	$stateValues = array($datum['status'], $datum['state'], $datum['is_default']);
	$conn->insertArray($stateTbl, $stateFields, array(array_combine($stateFields, $stateValues)));
}

$installer->endSetup();
