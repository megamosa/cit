<?php
/**
 * Install Schema Script
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createCityTable($setup);

        $setup->endSetup();
    }

    /**
     * Create city table
     *
     * @param SchemaSetupInterface $setup
     * @return void
     */
    private function createCityTable(SchemaSetupInterface $setup)
    {
        if (!$setup->tableExists('directory_country_region_city')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('directory_country_region_city')
            )->addColumn(
                'city_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'City ID'
            )->addColumn(
                'region_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Region ID'
            )->addColumn(
                'code',
                Table::TYPE_TEXT,
                32,
                [],
                'City Code'
            )->addColumn(
                'default_name',
                Table::TYPE_TEXT,
                255,
                [],
                'City Name'
            )->addIndex(
                $setup->getIdxName('directory_country_region_city', ['region_id']),
                ['region_id']
            )->addForeignKey(
                $setup->getFkName('directory_country_region_city', 'region_id', 'directory_country_region', 'region_id'),
                'region_id',
                $setup->getTable('directory_country_region'),
                'region_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Directory Country Region City'
            );
            $setup->getConnection()->createTable($table);
        }
    }
}