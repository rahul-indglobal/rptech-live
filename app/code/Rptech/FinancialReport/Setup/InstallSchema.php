<?php

namespace Rptech\FinancialReport\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Rptech\FinancialReport\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    const TABLE_NAME = 'financial_report';
    const DOC_TABLE_NAME = 'financial_report_docs';

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
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Title'
                )
                ->addColumn(
                    'sub_title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Sub Title'
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

            // Financial Report Docs Table code
            $doctable = $installer->getConnection()
                ->newTable($installer->getTable(self::DOC_TABLE_NAME))
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
                    'parent_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'unsigned' => true],
                    'Parent ID'
                )
                ->addColumn(
                    'document',
                    Table::TYPE_TEXT,
                    '64K',
                    ['nullable' => true, 'default' => null],
                    'Document'
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
                )->addForeignKey(
                    $installer->getFkName(self::DOC_TABLE_NAME, 'parent_id', self::TABLE_NAME, 'entity_id'),
                    'parent_id',
                    $installer->getTable(self::TABLE_NAME),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->setComment(
                    'Financial Report Doc Table'
                );
            $installer->getConnection()->createTable($doctable);
        }
        $installer->endSetup();
    }
}
