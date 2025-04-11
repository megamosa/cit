<?php
/**
 * Install Data Script
 */
declare(strict_types=1);

namespace MagoArab\RegionManager\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * Install Data
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Sample data for Egypt regions and cities
        $this->addEgyptSampleData($setup);

        $setup->endSetup();
    }

    /**
     * Add sample data for Egypt
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    private function addEgyptSampleData(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        
        // Check if Egypt exists in country table
        $countryTable = $setup->getTable('directory_country');
        $egyptExists = $connection->fetchOne(
            $connection->select()->from($countryTable, 'count(*)')
                ->where('country_id = ?', 'EG')
        );
        
        if (!$egyptExists) {
            // Add Egypt to country table if it doesn't exist
            $connection->insert(
                $countryTable,
                [
                    'country_id' => 'EG',
                    'iso2_code' => 'EG',
                    'iso3_code' => 'EGY'
                ]
            );
        }
        
        // Add Egypt regions
        $regionTable = $setup->getTable('directory_country_region');
        $regionNameTable = $setup->getTable('directory_country_region_name');
        
        $egyptRegions = [
            ['country_id' => 'EG', 'code' => 'CAI', 'default_name' => 'Cairo'],
            ['country_id' => 'EG', 'code' => 'ALX', 'default_name' => 'Alexandria'],
            ['country_id' => 'EG', 'code' => 'GIZ', 'default_name' => 'Giza'],
            ['country_id' => 'EG', 'code' => 'ASN', 'default_name' => 'Aswan'],
            ['country_id' => 'EG', 'code' => 'LUX', 'default_name' => 'Luxor']
        ];
        
        foreach ($egyptRegions as $region) {
            // Check if region already exists
            $regionExists = $connection->fetchOne(
                $connection->select()->from($regionTable, 'count(*)')
                    ->where('country_id = ?', $region['country_id'])
                    ->where('code = ?', $region['code'])
            );
            
            if (!$regionExists) {
                // Insert region
                $connection->insert($regionTable, $region);
                
                // Get inserted region ID
                $regionId = $connection->lastInsertId($regionTable);
                
                // Insert region name in Arabic
                $connection->insert(
                    $regionNameTable,
                    [
                        'locale' => 'ar_EG',
                        'region_id' => $regionId,
                        'name' => $this->getArabicName($region['default_name'])
                    ]
                );
            }
        }
        
        // Add Cairo cities
        $cairoRegionId = $connection->fetchOne(
            $connection->select()->from($regionTable, 'region_id')
                ->where('country_id = ?', 'EG')
                ->where('code = ?', 'CAI')
        );
        
        if ($cairoRegionId) {
            $cityTable = $setup->getTable('directory_country_region_city');
            
            $cairoCities = [
                ['region_id' => $cairoRegionId, 'code' => 'NASR', 'default_name' => 'Nasr City'],
                ['region_id' => $cairoRegionId, 'code' => 'MAADI', 'default_name' => 'Maadi'],
                ['region_id' => $cairoRegionId, 'code' => 'ZAMALEK', 'default_name' => 'Zamalek'],
                ['region_id' => $cairoRegionId, 'code' => 'HLWN', 'default_name' => 'Helwan'],
                ['region_id' => $cairoRegionId, 'code' => 'DWNTWN', 'default_name' => 'Downtown']
            ];
            
            foreach ($cairoCities as $city) {
                // Check if city already exists
                $cityExists = $connection->fetchOne(
                    $connection->select()->from($cityTable, 'count(*)')
                        ->where('region_id = ?', $city['region_id'])
                        ->where('code = ?', $city['code'])
                );
                
                if (!$cityExists) {
                    // Insert city
                    $connection->insert($cityTable, $city);
                }
            }
        }
        
        // Add Alexandria cities
        $alexRegionId = $connection->fetchOne(
            $connection->select()->from($regionTable, 'region_id')
                ->where('country_id = ?', 'EG')
                ->where('code = ?', 'ALX')
        );
        
        if ($alexRegionId) {
            $cityTable = $setup->getTable('directory_country_region_city');
            
            $alexCities = [
                ['region_id' => $alexRegionId, 'code' => 'STLY', 'default_name' => 'Stanley'],
                ['region_id' => $alexRegionId, 'code' => 'SMOUHA', 'default_name' => 'Smouha'],
                ['region_id' => $alexRegionId, 'code' => 'MNTZA', 'default_name' => 'Montazah'],
                ['region_id' => $alexRegionId, 'code' => 'AGLM', 'default_name' => 'Agami']
            ];
            
            foreach ($alexCities as $city) {
                // Check if city already exists
                $cityExists = $connection->fetchOne(
                    $connection->select()->from($cityTable, 'count(*)')
                        ->where('region_id = ?', $city['region_id'])
                        ->where('code = ?', $city['code'])
                );
                
                if (!$cityExists) {
                    // Insert city
                    $connection->insert($cityTable, $city);
                }
            }
        }
    }
    
    /**
     * Get Arabic name for sample data
     *
     * @param string $englishName
     * @return string
     */
    private function getArabicName($englishName)
    {
        $names = [
            'Cairo' => 'القاهرة',
            'Alexandria' => 'الإسكندرية',
            'Giza' => 'الجيزة',
            'Aswan' => 'أسوان',
            'Luxor' => 'الأقصر',
            'Nasr City' => 'مدينة نصر',
            'Maadi' => 'المعادي',
            'Zamalek' => 'الزمالك',
            'Helwan' => 'حلوان',
            'Downtown' => 'وسط البلد',
            'Stanley' => 'ستانلي',
            'Smouha' => 'سموحة',
            'Montazah' => 'المنتزه',
            'Agami' => 'العجمي'
        ];
        
        return isset($names[$englishName]) ? $names[$englishName] : $englishName;
    }
}