<?php

/**
 * @author Rptech
 * @package Rptech_Leadership
 */
namespace Rptech\Leadership\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Rptech\Leadership\Setup
 */
class InstallSchema implements InstallSchemaInterface {

    const TABLE_NAME = 'leadership';

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();
        $table = $setup->getConnection()->newTable(
            $setup->getTable(self::TABLE_NAME)
        )->addColumn('entity_id', Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ], 'Entity Id'
        )->addColumn(
            'title', Table::TYPE_TEXT, 255, ['nullable' => true, "default"=>null], 'Title'
        )->addColumn(
            "description", Table::TYPE_TEXT, '64k', ["nullable"=>true, "default"=>null], "Description"
        )->addColumn(
            'image', Table::TYPE_TEXT, null, ['nullable' => true], 'Image'
        )->addColumn(
            'updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Updated At'
        )->addColumn(
            'created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created At'
        );
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
