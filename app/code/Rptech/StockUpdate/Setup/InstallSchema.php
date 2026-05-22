<?php

namespace Rptech\StockUpdate\Setup;

/*use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;*/

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('rptech_auto_stock_update')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('rptech_auto_stock_update')
			)
				->addColumn(
					'row_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Row ID'
				)
				->addColumn(
					'sku',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable' => false],
					'Product SKU'
				)
				->addColumn(
					'qty',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					1,
					['nullable' => false],
					'Product Quantity'
				)
				->addColumn(
					'run_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
					null,
					['nullable' => true, 'default' => null],
					'Time to execute at'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					1,
					['nullable' => false],
					'Update Status (1-Pending to update, 2-Updated)'
				)
				->addColumn(
					'created_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
					'Created At'
				)
				->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At')
				->setComment('Post Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('rptech_auto_stock_update'),
				$setup->getIdxName(
					$installer->getTable('rptech_auto_stock_update'),
					['sku'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['sku'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}
		$installer->endSetup();
	}
}