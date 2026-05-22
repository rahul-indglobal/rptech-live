<?php
/**
 * @author Rptech
 * @package Rptech_Announcement
 */
namespace Rptech\Media\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Rptech\Brand\Setup\UpgradeSchema as BrandSchema;

/**
 * Class UpgradeSchema
 * @package Rptech\Media\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    const TABLE_MEDIA = "media";

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), "1.0.0", "<=")) {
            $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_MEDIA)
            )->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ], 'Entity Id'
            )->addColumn(
                'title', Table::TYPE_TEXT, 255, ['nullable' => true, "default" => null], 'Media Title'
            )->addColumn(
                'content', Table::TYPE_TEXT, '128k', ['nullable' => true, "default" => null], 'Content'
            )->addColumn(
                "brand_id", Table::TYPE_INTEGER, null, ["unsigned" => true, "nullable" => true, "default" => null], "Brand Id"
            )->addColumn(
                "is_active", Table::TYPE_BOOLEAN, null, ["nullable" => false, "default" => false], "Is Active"
            )->addColumn(
                'publish_date', Table::TYPE_TIMESTAMP, null, ['nullable' => true, 'default' => null], 'Publish Date'
            )->addColumn(
                'updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Updated At'
            )->addColumn(
                'created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created At'
            );
            $setup->getConnection()->createTable($table);
            $setup->endSetup();
        }
    }
}