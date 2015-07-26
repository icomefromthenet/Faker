<?php
namespace Faker\Tests\Engine\Seed\Mock;

use Faker\Components\Engine\Seed\TableSeedAbstract;
use Faker\Components\Engine\DB\Builder\ColumnBuilder;
use Faker\Components\Engine\DB\Builder\DatasourceBuilder;



class MockTableSeedSource extends TableSeedAbstract
{
    
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
    
    
    protected function datasourceCustomType(DatasourceBuilder $oBuilder, array $aOptions)
    {
        $oBuilder->createPHPSource()
                    ->setDataFromClosure(function(){
                        return new \ArrayIterator(array(1,2,3,4));
                    })
                    ->setDatasourceName('mocksourceA')
                ->end() // finish php source definition
        ->end(); // finish source builder
        
    }
    
    protected function getTableName() 
    {
        return 'mytablea';
    }
    
}
/* End of Class */