<?php

/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Rptech\ServiceAddress\Setup
 */
class InstallSchema implements InstallSchemaInterface {

    const TABLE_NAME = 'services_address';

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
            'city_name', Table::TYPE_TEXT, 255, ['nullable' => true, "default"=>null], 'City Name'
        )->addColumn(
            "address", Table::TYPE_TEXT, 255, ["nullable"=>true, "default"=>null], "Address"
        )->addColumn(
            'map_code', Table::TYPE_TEXT, 255, ['nullable' => true], 'Map code'
        )->addColumn(
            'latitude', Table::TYPE_TEXT, 255, ['nullable' => true], 'Latitude'
        )->addColumn(
            'longitude', Table::TYPE_TEXT, 255, ['nullable' => true], 'Longitude'
        )->addColumn(
            'updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Updated At'
        )->addColumn(
            'created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created At'
        );
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
