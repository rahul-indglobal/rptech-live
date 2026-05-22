<?php

namespace Rptech\CareerOpportunities\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Rptech\CareerOpportunities\Api\Data\CareerOpportunitiesInterface;

/**
 * Class InstallSchema
 * @package Rptech\CareerOpportunities\Setup
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
        $tableName = $installer->getTable(CareerOpportunitiesInterface::TABLE_NAME);

        if (!$installer->tableExists(CareerOpportunitiesInterface::TABLE_NAME)) {
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
                    'name',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => null],
                    'Name'
                )
                ->addColumn(
                    'post',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => null],
                    'Post'
                )
                ->addColumn(
                    'city',
                    Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'City'
                )
                ->addColumn(
                    'age',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Age'
                )
                ->addColumn(
                    'qualification',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Qualification'
                )
                ->addColumn(
                    'experience',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Experience'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Email'
                )
                ->addColumn(
                    'mobile',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Mobile'
                )
                ->addColumn(
                    'remark',
                    Table::TYPE_TEXT,
                    '64K',
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Remark'
                )
                ->addColumn(
                    'cv_file',
                    Table::TYPE_TEXT,
                    255,
                    ['unsigned' => true, 'nullable' => false, 'default' => null],
                    'Cv_File'
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
