<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

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
         *  Create tabkle 'bc_supplier_is'
         */
        if (!$installer->tableExists('bc_supplier_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_supplier_is')
            )
                    ->addColumn(
                        'supplier_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'supplier_id'
                    )
                    ->addColumn(
                        'created_at',
                        Table::TYPE_TIMESTAMP,
                        255,
                        ['nullable' => false],
                        'created_at'
                    )
                    ->addColumn(
                        'updated_at',
                        Table::TYPE_TIMESTAMP,
                        255,
                        ['nullable' => false],
                        'updated_at'
                    )
                    ->addColumn(
                        'first_name',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'first_name'
                    )
                    ->addColumn(
                        'last_name',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'last_name'
                    )
                    ->addColumn(
                        'company',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'company'
                    )
                    ->addColumn(
                        'contact_person',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'contact_person'
                    )
                    ->addColumn(
                        'email',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'email'
                    )
                    ->addColumn(
                        'password',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'password'
                    )
                    ->addColumn(
                        'shipment_method',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'shipment_method'
                    )
                    ->addColumn(
                        'payment_method',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => null, 'nullable' => false],
                        'payment_method'
                    )
                    ->addColumn(
                        'is_active',
                        Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false],
                        'is_active'
                    );
            $installer->getConnection()->createTable($table);
        }
        $installer->run("ALTER TABLE `{$installer->getTable('bc_supplier_is')}` ADD UNIQUE ( `email` )");

        /**
         *  Create table 'bc_supplier_address_is'
         */
        if (!$installer->tableExists('bc_supplier_address_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_supplier_address_is')
            )
                    ->addColumn(
                        'supplier_address_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'supplier_address_id'
                    )
                    ->addColumn(
                        'supplier_id',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'unsigned' => true, 'nullable' => false, ''],
                        'supplier_id'
                    )
                    ->addColumn(
                        'created_at',
                        Table::TYPE_TIMESTAMP,
                        255,
                        ['nullable' => false],
                        'created_at'
                    )
                    ->addColumn(
                        'updated_at',
                        Table::TYPE_TIMESTAMP,
                        255,
                        ['nullable' => false],
                        'updated_at'
                    )
                    ->addColumn(
                        'first_name',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'first_name'
                    )
                    ->addColumn(
                        'last_name',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'last_name'
                    )
                    ->addColumn(
                        'address_line1',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'address_line1'
                    )
                    ->addColumn(
                        'city',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'city'
                    )
                    ->addColumn(
                        'country',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'country'
                    )
                    ->addColumn(
                        'state',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'state'
                    )
                    ->addColumn(
                        'state_id',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'state_id'
                    )
                    ->addColumn(
                        'postal_code',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'postal_code'
                    )
                    ->addColumn(
                        'telephone',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'telephone'
                    )
                    ->addColumn(
                        'fax',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false],
                        'fax'
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'bc_supplier_address_is',
                            'supplier_id',
                            'bc_supplier_is',
                            'supplier_id'
                        ),
                        'supplier_id',
                        $installer->getTable('bc_supplier_is'),
                        'supplier_id',
                        Table::ACTION_CASCADE,
                        Table::ACTION_CASCADE
                    );
            $installer->getConnection()->createTable($table);
        }
        /**
         *  Create table 'bc_supplier_product_approve_is'
         */
        if (!$installer->tableExists('bc_supplier_product_approve_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_supplier_product_approve_is')
            )
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'id'
                    )
                    ->addColumn(
                        'supplier_id',
                        Table::TYPE_INTEGER,
                        11,
                        ['nullable' => false],
                        'supplier_id'
                    )
                    ->addColumn(
                        'product_id',
                        Table::TYPE_INTEGER,
                        11,
                        ['nullable' => false],
                        'product_id'
                    )
                    ->addColumn(
                        'approve_status',
                        Table::TYPE_INTEGER,
                        11,
                        ['nullable' => false],
                        'approve_status'
                    )
                    ->addColumn(
                        'new_prod_flag',
                        Table::TYPE_INTEGER,
                        11,
                        ['nullable' => false],
                        'new_prod_flag'
                    )
                    ->addColumn(
                        'supplier_status',
                        Table::TYPE_INTEGER,
                        11,
                        ['nullable' => false],
                        'supplier_status'
                    );
            $installer->getConnection()->createTable($table);
        }
        /**
         *  Create table 'bc_supplier_product_is'
         */
        if (!$installer->tableExists('bc_supplier_product_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_supplier_product_is')
            )
                    ->addColumn(
                        'rel_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'rel_id'
                    )
                    ->addColumn(
                        'supplier_id',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'unsigned' => true, 'nullable' => false, ''],
                        'supplier_id'
                    )
                    ->addColumn(
                        'product_id',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'unsigned' => true, 'nullable' => false],
                        'product_id'
                    )
                    ->addColumn(
                        'position',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'nullable' => false],
                        'position'
                    )
                    ->addIndex(
                        $installer->getIdxName(
                            'bc_supplier_product_is',
                            ['supplier_id', 'product_id'],
                            AdapterInterface::INDEX_TYPE_UNIQUE
                        ),
                        ['supplier_id', 'product_id'],
                        ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                    )
                    ->addForeignKey(
                        $installer->getFkName('bc_supplier_product_is', 'product_id', 'catalog_product_entity', 'entity_id'),
                        'product_id',
                        $installer->getTable('catalog_product_entity'),
                        'entity_id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName('bc_supplier_product_is', 'supplier_id', 'bc_supplier_is', 'supplier_id'),
                        'supplier_id',
                        $installer->getTable('bc_supplier_is'),
                        'supplier_id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Biztech Inventorysystem bc_supplier_product_is'
                    );
            $installer->getConnection()->createTable($table);
        }


        $insSetup = $this->_eavSetupFactory->create()->getSetup();
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $insSetup]);
        $eavSetup->updateAttribute(
            Product::ENTITY,
            'cost',
            'apply_to',
            'simple,grouped,configurable,virtual,bundle,downloadable'
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'bc_supplier_is',
            [
            'type' => 'varchar',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'frontend' => '',
            'label' => 'Select Supplier',
            'input' => 'multiselect',
            'class' => '',
            'global' => Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => 0,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'source' => 'Biztech\Inventorysystem\Model\Managesupplier',
            'apply_to' => 'simple,grouped,configurable,virtual,bundle,downloadable'
                ]
        );
        $eavSetup->addAttributeToGroup(Product::ENTITY, 'Default', 'General', 'bc_supplier_is');

        $eavSetup->addAttribute(
            Product::ENTITY,
            'reck_no',
            [
            'type' => 'varchar',
            'backend' => '',
            'frontend' => '',
            'label' => 'Reck Number',
            'input' => 'text',
            'class' => '',
            'global' => Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'default' => 0,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'unique' => false,
            'apply_to' => 'simple,grouped,configurable,virtual,bundle,downloadable'
                ]
        );

        $eavSetup->addAttributeToGroup(Product::ENTITY, 'Default', 'General', 'reck_no');

        if (!$installer->tableExists('bc_supplier_product_approve_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_supplier_product_approve_is')
            )
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'id'
                    )
                    ->addColumn(
                        'supplier_id',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'unsigned' => true, 'nullable' => false, ''],
                        'supplier_id'
                    )
                    ->addColumn(
                        'product_id',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'unsigned' => true, 'nullable' => false],
                        'product_id'
                    )
                    ->addColumn(
                        'approve_status',
                        Table::TYPE_TEXT,
                        255,
                        ['default' => 'pending', 'nullable' => false],
                        'approve_status'
                    )
                    ->addColumn(
                        'new_prod_flag',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'unsigned' => true, 'nullable' => false],
                        'new_prod_flag'
                    )
                    ->addColumn(
                        'supplier_status',
                        Table::TYPE_INTEGER,
                        10,
                        ['default' => 0, 'unsigned' => true, 'nullable' => false],
                        'supplier_status'
                    )
                    ->addIndex(
                        $installer->getIdxName(
                            'bc_supplier_product_approve_is',
                            ['supplier_id', 'product_id'],
                            AdapterInterface::INDEX_TYPE_UNIQUE
                        ),
                        ['supplier_id', 'product_id'],
                        ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                    )
                    ->addForeignKey(
                        $installer->getFkName('bc_supplier_product_approve_is', 'product_id', 'catalog_product_entity', 'entity_id'),
                        'product_id',
                        $installer->getTable('catalog_product_entity'),
                        'entity_id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName('bc_supplier_product_approve_is', 'supplier_id', 'bc_supplier_is', 'supplier_id'),
                        'supplier_id',
                        $installer->getTable('bc_supplier_is'),
                        'supplier_id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Biztech Inventorysystem bc_supplier_product_approve_is'
                    );
            $installer->getConnection()->createTable($table);
        }

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'bc_backordered',
            [
            'type' => Table::TYPE_SMALLINT,
            'nullable' => true,
            'default' => 0,
            'comment' => 'backordered if order placed above available stock',
                ]
        );

        /**
         *  Create table 'bc_purchaseorders_is'
         */
        if (!$installer->tableExists('bc_purchaseorders_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_purchaseorders_is')
            )
                    ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'id')
                    ->addColumn('purchase_order_id', Table::TYPE_TEXT, 20, ['nullable' => false])
                    ->addColumn('supplier_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false, ''], 'supplier_id')
                    ->addColumn('created_at', Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('updated_at', Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('required_date', Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('sales_order_id', Table::TYPE_TEXT, 255, ['nullable' => true], 'sales order increment id')
                    ->addColumn('status', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => 'pending'], 'PO Order Status')/* TODO of TYPE ENUM */
                    ->addColumn('total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true, 'default' => 0.00])
                    ->addColumn('ship_sub_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addColumn('ship_cost', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addColumn('ship_grand_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addColumn('shipment_method', Table::TYPE_TEXT, 100, ['nullable' => true])
                    ->addColumn('payment_method', Table::TYPE_TEXT, 100, ['nullable' => true])
                    ->addColumn('created_by', Table::TYPE_TEXT, 100, ['nullable' => true])
                    ->addColumn('invoiced', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'])
                    ->addColumn('mail_sent', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], '0=notsent; 1=sent')
                    ->addForeignKey(
                        $installer->getFkName('bc_purchaseorders_is', 'supplier_id', 'bc_supplier_is', 'supplier_id'),
                        'supplier_id',
                        $installer->getTable('bc_supplier_is'),
                        'supplier_id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Biztech Inventorysystem bc_purchaseorders_is'
                    );
            $installer->getConnection()->createTable($table);
        }

        /**
         *  Create table 'bc_purchaseorder_comments_is'
         */
        if (!$installer->tableExists('bc_purchaseorder_comments_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_purchaseorder_comments_is')
            )
                    ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'id')
                    ->addColumn('created_at', Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('purchaseorder_id', Table::TYPE_INTEGER, null, ['nullable' => false, 'unsigned' => true])
                    ->addColumn('purchaseorder_incr_id', Table::TYPE_TEXT, 255, ['nullable' => false])
                    ->addColumn('comment', Table::TYPE_TEXT, null, ['nullable' => false])
                    ->addForeignKey(
                        $installer->getFkName('bc_purchaseorder_comments_is', 'purchaseorder_id', 'bc_purchaseorders_is', 'id'),
                        'purchaseorder_id',
                        $installer->getTable('bc_purchaseorders_is'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Biztech Inventorysystem bc_purchaseorder_comments_is'
                    );
            $installer->getConnection()->createTable($table);
        }

        /**
         *  Create table 'bc_purchaseorder_items_is'
         */
        if (!$installer->tableExists('bc_purchaseorder_items_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_purchaseorder_items_is')
            )
                    ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'id')
                    ->addColumn('purchase_order_id', Table::TYPE_INTEGER, null, ['nullable' => false, 'unsigned' => true])
                    ->addColumn('product_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false], 'product_id')
                    ->addColumn('product_name', Table::TYPE_TEXT, 255, ['nullable' => true])
                    ->addColumn('product_sku', Table::TYPE_TEXT, 255, ['nullable' => true])
                    ->addColumn('sales_order_id', Table::TYPE_TEXT, 255, ['nullable' => true], 'increment_id')
                    ->addColumn('qty_ordered', Table::TYPE_INTEGER, null, ['nullable' => true])
                    ->addColumn('qty_avail', Table::TYPE_TEXT, 255, ['nullable' => false])/* TODO of TYPE ENUM */
                    ->addColumn('qty_purchased', Table::TYPE_INTEGER, null, ['nullable' => false])
                    ->addColumn('qty_received', Table::TYPE_INTEGER, null, ['nullable' => true])
                    ->addColumn('cost', Table::TYPE_DECIMAL, [12, 2], ['nullable' => false], 'Unit Cost')
                    ->addColumn('row_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addColumn('rec_row_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true], 'Stock Received Row Total')
                    ->addForeignKey(
                        $installer->getFkName('bc_purchaseorder_items_is', 'purchase_order_id', 'bc_purchaseorders_is', 'id'),
                        'purchase_order_id',
                        $installer->getTable('bc_purchaseorders_is'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Biztech Inventorysystem bc_purchaseorder_items_is'
                    );
            $installer->getConnection()->createTable($table);
        }

        /**
         *  Create table 'bc_inventorysystem_is'
         */
        if (!$installer->tableExists('bc_inventorysystem_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_inventorysystem_is')
            )
                    ->addColumn(
                        'inventorysystem_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'bc_inventorysystem_is'
                    )
                    ->addColumn(
                        'title',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        ['nullable' => false],
                        'title'
                    )
                    ->addColumn(
                        'filename',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        ['nullable' => false],
                        'filename'
                    )
                    ->addColumn(
                        'content',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        ['nullable' => false],
                        'content'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['default' => 0, 'nullable' => false],
                        'status'
                    )
                    ->addColumn(
                        'created_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'created_time'
                    )
                    ->addColumn(
                        'update_time',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'update_time'
                    );

            $installer->getConnection()->createTable($table);
        }

        /**
         *  Create table 'bc_stockreceived_is'
         */
        if (!$installer->tableExists('bc_stockreceived_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_stockreceived_is')
            )
                    ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'id')
                    ->addColumn('stockreceived_id', Table::TYPE_TEXT, 20, ['nullable' => false], 'stockreceived_id')
                    ->addColumn('purchaseorder_id', Table::TYPE_TEXT, 20, ['nullable' => false], 'purchaseorder_id')
                    ->addColumn('supplier_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false, ''], 'supplier_id')
                    ->addColumn('created_at', Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('updated_at', Table::TYPE_TIMESTAMP, 255, ['nullable' => true], 'updated_at')
                    ->addColumn('status', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => 'pending'], 'PO order status')
                    ->addColumn('sub_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])/* TODO of TYPE ENUM */
                    ->addColumn('shipping_cost', Table::TYPE_DECIMAL, [12, 2], ['nullable' => false])
                    ->addColumn('total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true, 'default' => '0.00'], 'Grand Total')
                    ->addColumn('received_by', Table::TYPE_TEXT, 100, ['nullable' => true])
                    ->addColumn('comment', Table::TYPE_TEXT, 255, ['nullable' => false])
                    ->addForeignKey(
                        $installer->getFkName('bc_stockreceived_is', 'supplier_id', 'bc_supplier_is', 'supplier_id'),
                        'supplier_id',
                        $installer->getTable('bc_supplier_is'),
                        'supplier_id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Biztech Inventorysystem bc_stockreceived_is'
                    );
            $installer->getConnection()->createTable($table);
        }

        /**
         *  Create table 'bc_stockreceived_items_is'
         */
        if (!$installer->tableExists('bc_stockreceived_items_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_stockreceived_items_is')
            )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'id'
                    )
                    ->addColumn('stockreceived_id', Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false], 'stockreceived_id')
                    ->addColumn('product_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false], 'product_id')
                    ->addColumn('product_name', Table::TYPE_TEXT, 255, ['nullable' => true])
                    ->addColumn('product_sku', Table::TYPE_TEXT, 255, ['nullable' => true])
                    ->addColumn('qty_avail', Table::TYPE_TEXT, 255, ['nullable' => false])
                    ->addColumn('qty_purchased', Table::TYPE_INTEGER, null, ['nullable' => false])
                    ->addColumn('qty_received', Table::TYPE_INTEGER, null, ['nullable' => true])
                    ->addColumn('cost', Table::TYPE_DECIMAL, [12, 2], ['nullable' => false], 'Unit Cost')
                    ->addColumn('row_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addForeignKey(
                        $installer->getFkName('bc_stockreceived_items_is', 'stockreceived_id', 'bc_stockreceived_is', 'id'),
                        'stockreceived_id',
                        $installer->getTable('bc_stockreceived_is'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment(
                        'Biztech Inventorysystem bc_stockreceived_is'
                    );


            $installer->getConnection()->createTable($table);
        }

        /**
         *  Update table 'bc_stockreceived_items_is'
         */
        if ($setup->getConnection()->isTableExists($setup->getTable('bc_stockreceived_items_is'))) {
            $setup->getConnection()->addColumn(
                $setup->getTable('bc_stockreceived_items_is'),
                'warehouse_id',
                ['type' => Table::TYPE_INTEGER, 10, 'nullable' => true, 'comment' => 'Warehouse Id']
            );
        }

        /**
         *  Update table 'bc_stockreceived_items_is'
         */
        if ($setup->getConnection()->isTableExists($setup->getTable('bc_purchaseorder_items_is'))) {
            $setup->getConnection()->addColumn(
                $setup->getTable('bc_purchaseorder_items_is'),
                'warehouse_id',
                ['type' => Table::TYPE_INTEGER, 10, 'nullable' => true, 'comment' => 'Warehouse Id']
            );
        }

        /**
         *  Create table 'bc_purchaseinvoice_is'
         */
        if (!$installer->tableExists('bc_purchaseinvoice_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_purchaseinvoice_is')
            )
                    ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'id')
                    ->addColumn('invoice_incr_id', Table::TYPE_TEXT, 20, ['nullable' => false])
                    ->addColumn('po_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false, ''], 'po_id')
                    ->addColumn('purchase_order_id', Table::TYPE_TEXT, 20, ['nullable' => false])
                    ->addColumn('supplier_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false, ''], 'supplier_id')
                    ->addColumn('created_at', Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('updated_at', Table::TYPE_TIMESTAMP, 255, ['nullable' => false])
                    ->addColumn('sub_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true, 'default' => 0.00])
                    ->addColumn('ship_cost', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addColumn('tax_amount', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true, 'default' => 0.00])
                    ->addColumn('discount', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true, 'default' => 0.00])
                    ->addColumn('grand_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addColumn('mail_sent', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], '0=notsent; 1=sent')
                    ->addColumn('comments', Table::TYPE_TEXT, 100, ['nullable' => false])
                    ->addColumn('append_comments', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], '0=notsent; 1=sent')
                    ->addColumn('invoice_status', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => 'pending'])/* TODO of TYPE ENUM */
                    ->addForeignKey(
                        $installer->getFkName('bc_purchaseinvoice_is', 'supplier_id', 'bc_supplier_is', 'supplier_id'),
                        'supplier_id',
                        $installer->getTable('bc_supplier_is'),
                        'supplier_id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName('bc_purchaseinvoice_is', 'po_id', 'bc_purchaseorders_is', 'id'),
                        'po_id',
                        $installer->getTable('bc_purchaseorders_is'),
                        'id',
                        Table::ACTION_CASCADE
                    );
            $installer->getConnection()->createTable($table);
        }

        /**
         *  Create table 'bc_purchaseinvoice_items_is'
         */
        if (!$installer->tableExists('bc_purchaseinvoice_items_is')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bc_purchaseinvoice_items_is')
            )
                    ->addColumn('id', Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'id')
                    ->addColumn('invoice_id', Table::TYPE_INTEGER, null, ['nullable' => false, 'unsigned' => true])
                    ->addColumn('product_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false], 'product_id')
                    ->addColumn('warehouse_id', Table::TYPE_INTEGER, 10, ['default' => 0, 'unsigned' => true, 'nullable' => false], 'warehouse_id')
                    ->addColumn('product_name', Table::TYPE_TEXT, 255, ['nullable' => true])
                    ->addColumn('product_sku', Table::TYPE_TEXT, 255, ['nullable' => true])
                    ->addColumn('qty_purchased', Table::TYPE_INTEGER, null, ['nullable' => false])
                    ->addColumn('qty_received', Table::TYPE_INTEGER, null, ['nullable' => true])
                    ->addColumn('cost', Table::TYPE_DECIMAL, [12, 2], ['nullable' => false], 'Unit Cost')
                    ->addColumn('row_total', Table::TYPE_DECIMAL, [12, 2], ['nullable' => true])
                    ->addForeignKey(
                        $installer->getFkName('bc_purchaseinvoice_items_is', 'invoice_id', 'bc_purchaseinvoice_is', 'id'),
                        'invoice_id',
                        $installer->getTable('bc_purchaseinvoice_is'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName('bc_purchaseinvoice_items_is', 'product_id', 'catalog_product_entity', 'entity_id'),
                        'product_id',
                        $installer->getTable('catalog_product_entity'),
                        'entity_id',
                        Table::ACTION_CASCADE
                    );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
