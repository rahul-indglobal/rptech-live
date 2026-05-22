<?php

namespace Rptech\Feedback\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Rptech\Feedback\Api\Data\FeedbackInterface;

/**
 * Class InstallSchema
 * @package Rptech\Feedback\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable(FeedbackInterface::TABLE_NAME);

        if (!$installer->tableExists(FeedbackInterface::TABLE_NAME)) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Entity ID'
                )
                ->addColumn(
                    'type',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => null],
                    'Type'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => null],
                    'Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Description'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Email'
                )
                ->addColumn(
                    'phone',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Phone'
                )
                ->addColumn(
                    'address',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Address'
                )
                ->addColumn(
                    'state',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'State'
                )
                ->addColumn(
                    'mobile',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Mobile'
                )
                ->addColumn(
                    'pincode',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Pincode'
                )
                ->addColumn(
                    'city',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'City'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
