<?php
namespace Faker\Tests\Engine\Seed\Mock;

use Faker\Components\Engine\Seed\SchemaSeedAbstract;
use Faker\Components\Engine\DB\Builder\TableBuilder;

class MockSchemaSeed extends SchemaSeedAbstract
{
    
    protected function getLocaleType()
    {
        return 'en';
    }
    
    protected function getRandomGeneratorType()
    {
        return 'simple';
    }
    
    
    protected function getTables()
    {
        $oContainer = $this->getContainer();
        
        return array(
            'mytablea'   => new  MockTableSeedBasic($oContainer,100),
            'otherTable' => 'tableOtherTable'
        );
        
    }
    
    
    protected function tableOtherTable(TableBuilder $oBulder, array $aOptions)
    {
          $oBulder->toGenerate(100)
                    ->addColumn('column1')
                        ->dbalType('string')
                        ->addField()
                            ->fieldCity()
                                ->countries(array('AU'))
                            ->end() //  end field definition
                        ->end() // end field builder
                    ->end(); // end column builder
    }
    
    
    protected function getPassName() 
    {
        return 'passA';
    }
    
    protected function getSchemaName()
    {
        return 'schemaA';
    }
    
}
/* End of Class */