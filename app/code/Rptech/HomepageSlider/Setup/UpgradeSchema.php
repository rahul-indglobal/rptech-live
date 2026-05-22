<?php
/**
 * @author Rptech
 * @package Rptech_HomepageSlider
 */
namespace Rptech\HomepageSlider\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Rptech\HomepageSlider\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    const TABLE_HOMEPAGESLIDER = "homepageslider";

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), "1.0.0", "<=")) {
            $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_HOMEPAGESLIDER)
            )->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ], 'Entity Id'
            )->addColumn(
                'title', Table::TYPE_TEXT, '64K', ['nullable' => true, "default" => null], 'Slider Title'
            )->addColumn(
                'sort_position', Table::TYPE_INTEGER, 11, ['nullable' => true, "default" => 0], 'Sort Position'
            )->addColumn(
                "image", Table::TYPE_TEXT, '64k', ["nullable" => true, "default" => null], "Image"
            )->addColumn(
                "is_active", Table::TYPE_BOOLEAN, null, ["nullable" => false, "default" => false], "Is Active"
            )->addColumn(
                'updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Updated At'
            )->addColumn(
                'created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created At'
            );
            $setup->getConnection()->createTable($table);
            $setup->endSetup();
        }
        if (version_compare($context->getVersion(), "1.0.1", "<=")) {
            $installer = $setup;
            $installer->startSetup();
            $installer->getConnection()->addColumn(
                $installer->getTable('homepageslider'), 'link', [
                    'type' => Table::TYPE_TEXT,
                    'default' => null,
                    'after' => "image",
                    'comment' => 'Direct link',
                ]
            );
            $installer->endSetup();
        }
    }
}