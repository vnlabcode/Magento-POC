<?php
namespace Rokanthemes\SlideBanner\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface{
	/**
	 * {@inheritdoc}
	 */
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $installer = $setup;

        $installer->startSetup();
        /**
         * Install eav entity types to the eav/entity_type table
         */
        
        $installer->getConnection()->dropTable($installer->getTable('rokanthemes_slider'));
            $installer->getConnection()->dropTable($installer->getTable('rokanthemes_slide'));
            $table = $installer->getConnection()
                ->newTable($installer->getTable('rokanthemes_slider'))
                ->addColumn(
                    'slider_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Slider ID'
                )
                ->addColumn(
                    'slider_identifier',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Identifier'
                )
                ->addColumn(
                    'slider_title',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Slider title'
                )->addColumn(
                    'slider_status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    6,
                    ['nullable' => true, 'default'=>1],
                    'Status'
                )->addColumn(
                    'slider_setting',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Setting slider'
                )->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Created Date'
                )
                ->addIndex(
                    'slider_identifier',
                    ['slider_identifier']
                )
                ->setComment('Slider');

            $installer->getConnection()
                ->createTable($table);


            $table = $installer->getConnection()
                ->newTable($installer->getTable('rokanthemes_slide'))
                ->addColumn(
                    'slide_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Slide ID'
                )
                ->addColumn(
                    'slider_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    10,
                    ['nullable' => false],
                    'Slider ID'
                )
                ->addColumn(
                    'store_ids',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Store Ids'
                )->addColumn(
                    'slide_status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    6,
                    ['nullable' => true, 'default' => '1'],
                    'Status'
                )->addColumn(
                    'slide_position',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    10,
                    ['nullable' => true],
                    'Slide Position'
                )->addColumn(
                    'slide_type',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Slide Type'
                )->addColumn(
                    'slide_video',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Slide Video'
                )->addColumn(
                    'slide_image',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Slide image'
                )->addColumn(
                    'slide_image_mobile',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Slide image mobile'
                )->addColumn(
                    'slide_link',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Slide Link'
                )->addColumn(
                    'opennewtab',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    50,
                    ['nullable' => true],
                    'Open New Tab'
                )->addColumn(
                    'slide_text',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Slide Text'
                )
                ->addColumn(
                    'text_position',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Text position'
                )
                ->addColumn(
                    'text_animate',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true],
                    'Text animate'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [],
                    'Creation Time'
                )
                ->setComment('Slide Item');
            $installer->getConnection()
                ->createTable($table);

        $installer->endSetup();
    }
}
