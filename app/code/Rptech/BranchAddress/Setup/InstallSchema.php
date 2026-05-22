<?php

namespace Rptech\BranchAddress\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Rptech\BranchAddress\Api\Data\BranchAddressInterface;

/**
 * Class InstallSchema
 * @package Rptech\BranchAddress\Setup
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
        $tableName = $installer->getTable(BranchAddressInterface::TABLE_NAME);

        if (!$installer->tableExists(BranchAddressInterface::TABLE_NAME)) {
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
                    'branch_location',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => null],
                    'Branch Location'
                )
                ->addColumn(
                    'branch_address',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => null],
                    'Branch Address'
                )
                ->addColumn(
                    'branch_phone',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Branch Phone'
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
