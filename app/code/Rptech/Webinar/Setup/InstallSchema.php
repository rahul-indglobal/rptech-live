<?php
namespace Rptech\Webinar\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
	{
		$setup->startSetup();

		if (!$setup->tableExists('rptech_webinar')) {
			$table = $setup->getConnection()->newTable(
				$setup->getTable('rptech_webinar')
			)
				->addColumn(
					'id',
					Table::TYPE_INTEGER,
					null,
					['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
					'ID'
				)
				->addColumn(
					'customer_type',
					Table::TYPE_TEXT,
					100,
					['nullable' => true],
					'Type of Customer'
				)
				->addColumn(
					'person_name',
					Table::TYPE_TEXT,
					255,
					['nullable' => true],
					'Name of the Person'
				)
				->addColumn(
					'email',
					Table::TYPE_TEXT,
					255,
					['nullable' => true],
					'Email ID'
				)
				->addColumn(
					'contact',
					Table::TYPE_TEXT,
					32,
					['nullable' => true],
					'Contact Number'
				)
				->addColumn(
					'location',
					Table::TYPE_TEXT,
					255,
					['nullable' => true],
					'Location'
				)
				->addColumn(
					'company',
					Table::TYPE_TEXT,
					255,
					['nullable' => true],
					'Name of Institute / Company'
				)
				->addColumn(
					'website',
					Table::TYPE_TEXT,
					255,
					['nullable' => true],
					'Website Address'
				)
				->addColumn(
					'area',
					Table::TYPE_TEXT,
					100,
					['nullable' => true],
					'Area of Work'
				)
				->addColumn(
					'topics',
					Table::TYPE_TEXT,
					null,
					['nullable' => true],
					'Topic(s) of Interest'
				)
				->addColumn(
					'created_at',
					Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
					'Created At'
				)
				->setComment('Webinar Form Submissions');

			$setup->getConnection()->createTable($table);
		}

		$setup->endSetup();
	}
}
