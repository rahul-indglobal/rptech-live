<?php
/**
 * Copyright © Biztech, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Biztech\Inventorysystem\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @param  SchemaSetupInterface   $setup
     * @param  ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('bc_supplier_address_is');

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->changeColumn(
                    $tableName,
                    'address_line1',
                    'address_line',
                    ['type' => Table::TYPE_TEXT, 'nullable' => false],
                    'Address Line'
                );
            }
        }
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
        }
        if (version_compare($context->getVersion(), '1.1.1', '<')) {
        }

        $setup->endSetup();
    }
}
