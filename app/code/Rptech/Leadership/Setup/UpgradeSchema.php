<?php

namespace Rptech\Leadership\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){
		$installer = $setup;
		$installer->startSetup();

		if(version_compare($context->getVersion(), '1.1.0', '<')) {
			$installer->getConnection()->addColumn(
				$installer->getTable( 'leadership' ),
				'type',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'length' => 100,
					'comment' => 'Type',
					'after' => 'description'
				]
			);
		}

		$installer->endSetup();
	}
}