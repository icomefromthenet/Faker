<?php
namespace Faker\Tests\Engine\Seed\Mock;

use Faker\Components\Engine\Seed\TableSeedAbstract;
use Faker\Components\Engine\DB\Builder\ColumnBuilder;


class MockTableSeedExtras extends TableSeedAbstract
{
    
    protected function getLocaleType()
    {
        return 'en';
    }
    
    protected function getRandomGeneratorType()
    {
        return 'simple';
    }
    
    protected function getColumns()
    {
        $oContainer = $this->getContainer();
        
        return array(
            'columna1' => new MockColumnBasic($oContainer),
            'columnBname' => 'columnBname'
        );
        
    }
    
    
    protected function columnBname(ColumnBuilder $oBulder, array $aOptions)
    {
        $oBulder->dbalType('string')
                    ->addField('column2')
                        ->fieldAlphaNumeric()
                            ->format('cccCCCCCCC')
                        ->end()
                    ->end(); // finish building the field
        
    }
    
    
    protected function getTableName() 
    {
        return 'mytablea';
    }
    
}
/* End of Class */