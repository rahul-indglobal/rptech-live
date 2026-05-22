<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystemadvance\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param  SchemaSetupInterface   $setup
     * @param  ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'bc_warehouses_is'
         */
        if (!$installer->tableExists('bc_warehouses_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_warehouses_is')
            )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'bc_warehouses_is'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false],
                        'created_at'
                    )
                    ->addColumn(
                        'warehouse_name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        25,
                        ['nullable' => false],
                        'warehouse_name'
                    )
                    ->addColumn(
                        'manager',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'manager'
                    )
                    ->addColumn(
                        'telephone',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        25,
                        ['nullable' => false],
                        'telephone'
                    )
                    ->addColumn(
                        'street',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'street'
                    )
                    ->addColumn(
                        'city',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'city'
                    )
                    ->addColumn(
                        'country',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'country'
                    )
                    ->addColumn(
                        'state',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'state'
                    )
                    ->addColumn(
                        'state_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        25,
                        [],
                        'state_id'
                    )
                    ->addColumn(
                        'postal_code',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        25,
                        ['nullable' => false],
                        'postal_code'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false],
                        'status'
                    )
                    ->addColumn(
                        'primary_warehouse',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false],
                        'primary_warehouse'
                    );
            $installer->getConnection()->createTable($table);
        }
        $installer->run("ALTER TABLE `{$installer->getTable('bc_warehouses_is')}` ADD UNIQUE ( `warehouse_name` )");
        $installer->run("INSERT INTO `{$installer->getTable('bc_warehouses_is')}` (`id`, `warehouse_name`, `manager`, `telephone`, `street`, `city`, `country`, `state`, `state_id`, `postal_code`, `status`, `primary_warehouse`) VALUES (NULL, 'Default', '', '+1 5555555555', '1590,Peter Road', 'Adamsville', 'US', NULL, 'AL', '35014', '1', '1');");

        $installer->run("ALTER TABLE `{$installer->getTable('sales_order_item')}` ADD `warehouse_id` INT( 10 ) NULL");

        /**
         * Create table 'bc_warehouse_product_is'
         */
        if (!$installer->tableExists('bc_warehouse_product_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_warehouse_product_is')
            )
                    ->addColumn(
                        'rel_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'bc_warehouse_product_is'
                    )
                    ->addColumn(
                        'warehouse_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'nullable' => false, 'unsigned' => true],
                        'warehouse_id'
                    )
                    ->addColumn(
                        'product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        ['nullable' => false, 'unsigned' => true],
                        'product_id'
                    )
                    ->addColumn(
                        'position',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        ['nullable' => false],
                        'position'
                    )
                    ->addColumn(
                        'quantity',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        ['nullable' => false],
                        'quantity'
                    )
                    ->addColumn(
                        'priority',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        10,
                        ['nullable' => false],
                        'priority'
                    )
            ;
            $installer->getConnection()->createTable($table);
        }
        $installer->run("ALTER TABLE `{$installer->getTable('bc_warehouse_product_is')}` ADD KEY ( `warehouse_id` )");
        $installer->run("ALTER TABLE `{$installer->getTable('bc_warehouse_product_is')}` ADD KEY ( `product_id` )");
        $installer->run("ALTER TABLE `{$installer->getTable('bc_warehouses_is')}` ADD INDEX ( `id` )");

        $installer->run("ALTER TABLE `{$installer->getTable('bc_warehouse_product_is')}` ADD CONSTRAINT `bc_warehouse_product_is_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `{$installer->getTable('bc_warehouses_is')}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

        $installer->run("ALTER TABLE `{$installer->getTable('bc_warehouse_product_is')}` ADD CONSTRAINT `bc_warehouse_product_is_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE;");



        $insSetup = $this->_eavSetupFactory->create()->getSetup();
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $insSetup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'bc_warehouse_product',
            [
            'group' => 'MageMob Inventory',
            'type' => 'text',
            'input' => 'text',
            'backend' => '',
            'input_renderer' => 'Biztech\Inventorysystemadvance\Block\Adminhtml\Catalog\Product\Edit\Tabs', //definition of renderer
            'label' => 'Warehouse',
            'class' => '',
            'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_WEBSITE,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false,
            'apply_to' => 'simple,grouped,configurable,bundle',
            'is_configurable' => false,
                ]
        );

        /**
         * Create table 'bc_managehistory_is'
         */
        if (!$installer->tableExists('bc_managehistory_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_managehistory_is')
            )
                    ->addColumn('managehistory_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'managehistory_id')
                    ->addColumn('sku', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false])
                    ->addColumn('update_date', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('system_action', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 50, ['nullable' => true])
                    ->addColumn('action_type', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 30, ['nullable' => true])
                    ->addColumn('qty_before', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => false])
                    ->addColumn('qty_processed', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => true])
                    ->addColumn('final_qty', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => false], 'Unit Cost')
                    ->addColumn('warhouse_qty_before', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => false], 'Unit Cost')
                    ->addColumn('warhouse_qty_after', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => false], 'Unit Cost')
                    ->addColumn('interface', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20, ['nullable' => true])
                    ->addColumn('user', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 100, ['nullable' => true])
                    ->addColumn('order', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true]);
            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'bc_barcode_is'
         */
        if (!$installer->tableExists('bc_barcode_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_barcode_is')
            )
                    ->addColumn('id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'id')
                    ->addColumn('barcode', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 30, ['nullable' => false])
                    ->addColumn('created_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('updated_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('product_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => true])
                    ->addColumn('supplier', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => true])
                    ->addColumn('barcode_qty', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 50, ['nullable' => false])
                    ->addColumn('purchaseorder_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => true])
                    ->addColumn('status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => false], 'Unit Cost');
            $installer->getConnection()->createTable($table);
            $installer->run("ALTER TABLE `{$installer->getTable('bc_barcode_is')}` ADD UNIQUE ( `barcode` )");
        }
        $installer->endSetup();
    }
}
