<?php


namespace Rokanthemes\SetProduct\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (!$installer->tableExists('rokanthemes_setproduct')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('rokanthemes_setproduct')) 
                ->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'  => true
                ], 'Id')
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					150,
					[ 'nullable' => false],
					'Name'
				)->setComment('Rokanthemes Product Set');

            $installer->getConnection()->createTable($table); 
        }

        $installer->endSetup();
    }
}
