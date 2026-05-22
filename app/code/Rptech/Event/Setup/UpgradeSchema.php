<?php

namespace Rptech\Event\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    const TABLE_EVENT = "event";
    const TABLE_EVENT_IMAGES = "event_images";

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), "1.0.0", "<=")) {
            $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_EVENT)
            )->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ], 'Entity Id'
            )->addColumn(
                'title', Table::TYPE_TEXT, 255, ['nullable' => true, "default" => null], 'Title'
            )->addColumn(
                "description", Table::TYPE_TEXT, '64k', ["nullable" => true, "default" => null], "Description"
            )->addColumn(
                'sort_position', Table::TYPE_INTEGER, 11, ['nullable' => true, "default" => 0], 'Sort Position'
            )->addColumn(
                'updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Updated At'
            )->addColumn(
                'created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created At'
            );
            $setup->getConnection()->createTable($table);
            $setup->endSetup();


            $setup->startSetup();
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_EVENT_IMAGES)
            )->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ], 'Entity Id'
            )->addColumn("event_entity_id", Table::TYPE_INTEGER, null, [
                    'unsigned' => true,
                    "nullable" => false]
                , "Event Entity Id"
            )->addColumn(
                "image", Table::TYPE_TEXT, '64k', ["nullable" => true, "default" => null], "Image"
            )->addColumn(
                'image_sort_position', Table::TYPE_INTEGER, 11, ['nullable' => true, "default" => 0], 'Image Sort Position'
            )->addColumn(
                'updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Updated At'
            )->addColumn(
                'created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created At'
            )->addForeignKey(
                $setup->getFkName(self::TABLE_EVENT_IMAGES, 'event_entity_id', self::TABLE_EVENT, 'entity_id'),
                'event_entity_id',
                self::TABLE_EVENT,
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
                ->setComment("Event Images Table");
            $setup->getConnection()->createTable($table);
            $setup->endSetup();
        }

        if (version_compare($context->getVersion(), "1.0.1", "<=")) {
            $installer = $setup;
            $installer->startSetup();
            $connection = $installer->getConnection();
            if ($connection->tableColumnExists(self::TABLE_EVENT, 'city') === false) {
                $installer->getConnection()->addColumn(
                    $installer->getTable(self::TABLE_EVENT), 'city', [
                        'type' => Table::TYPE_TEXT,
                        'nullable' => true,
                        'size' => 255,
                        'after' => 'title',
                        'comment' => 'City',
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable(self::TABLE_EVENT), 'is_active', [
                        'type' => Table::TYPE_BOOLEAN,
                        'nullable' => false,
                        'size' => null,
                        'after' => 'city',
                        'default' => false,
                        'comment' => 'Is Active',
                    ]
                );
                $installer->endSetup();
            }
        }

        if (version_compare($context->getVersion(), "1.0.2", "<=")) {
            $installer = $setup;
            $installer->startSetup();
            $connection = $installer->getConnection();
            if ($connection->tableColumnExists(self::TABLE_EVENT, 'publish_date') === false) {
                $installer->getConnection()->addColumn(
                    $installer->getTable(self::TABLE_EVENT), 'publish_date', [
                        'type' => Table::TYPE_TIMESTAMP,
                        'nullable' => true,
                        'after' => 'sort_position',
                        'comment' => 'Publish Date',
                    ]
                );
                $installer->endSetup();
            }
        }
    }
}
