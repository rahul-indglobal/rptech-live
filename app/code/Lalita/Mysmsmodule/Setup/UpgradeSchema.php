<?php

namespace Lalita\Mysmsmodule\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
	/**
	 * @param SchemaSetupInterface $setup
	 * @param ModuleContextInterface $context
	 * @return void
	 */
	public function upgrade(
		SchemaSetupInterface $setup,
		ModuleContextInterface $context
	) {
		$setup->startSetup();

		if (version_compare($context->getVersion(), '1.0.1', '<')) {

			$connection = $setup->getConnection();
			$tableName  = $setup->getTable('rptech_enquiry_form');

			if ($connection->isTableExists($tableName)) {

				// user_type
				if (!$connection->tableColumnExists($tableName, 'user_type')) {
					$connection->addColumn(
						$tableName,
						'user_type',
						[
							'type'     => Table::TYPE_TEXT,
							'length'   => 255,
							'nullable' => true,
							'comment'  => 'User Type'
						]
					);
				}

				// where_parts_will_be_used
				if (!$connection->tableColumnExists($tableName, 'where_parts_will_be_used')) {
					$connection->addColumn(
						$tableName,
						'where_parts_will_be_used',
						[
							'type'     => Table::TYPE_TEXT,
							'length'   => 255,
							'nullable' => true,
							'comment'  => 'Where Parts Will Be Used'
						]
					);
				}

				// company_name
				if (!$connection->tableColumnExists($tableName, 'company_name')) {
					$connection->addColumn(
						$tableName,
						'company_name',
						[
							'type'     => Table::TYPE_TEXT,
							'length'   => 255,
							'nullable' => true,
							'comment'  => 'Company Name'
						]
					);
				}

				// quantity (varchar as requested)
				if (!$connection->tableColumnExists($tableName, 'quantity')) {
					$connection->addColumn(
						$tableName,
						'quantity',
						[
							'type'     => Table::TYPE_TEXT,
							'length'   => 255,
							'nullable' => true,
							'comment'  => 'Quantity'
						]
					);
				}
			}
		}

		$setup->endSetup();
	}
}
