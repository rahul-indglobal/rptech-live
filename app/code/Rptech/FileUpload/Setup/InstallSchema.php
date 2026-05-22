<?php

namespace Rptech\FileUpload\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Rptech\FileUpload\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    const TABLE_NAME = 'fileupload';

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable(self::TABLE_NAME);

        if (!$installer->tableExists(self::TABLE_NAME)) {
            $table = $installer->getConnection()
                ->newTable( $installer->getTable(self::TABLE_NAME))
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
                    'file_name',
                    Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => true, 'default' => null],
                    'File Name'
                )
                ->addColumn(
                    'link',
                    Table::TYPE_TEXT,
                    '64k',
                    ['nullable' => true, 'default' => null],
                    'File Link'
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
