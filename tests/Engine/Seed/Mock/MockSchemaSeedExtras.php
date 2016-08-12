<?php
namespace Faker\Tests\Engine\Seed\Mock;

use Faker\Components\Engine\Seed\SchemaSeedAbstract;
use Faker\Components\Engine\DB\Builder\TableBuilder;
use Faker\Components\Engine\Common\Formatter\FormatterBuilder;
use Faker\Components\Engine\DB\Builder\DatasourceBuilder;

class MockSchemaSeedExtras extends SchemaSeedAbstract
{
    
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
    
    protected function writterMysql(FormatterBuilder $oBuilder, array $aOptions)
    {
        
        $oBuilder->phpUnitWritter()
                        ->outputEncoding('utf8')
                            ->outFileFormat('{prefix}_{body}_{suffix}.{ext}')
                 ->end();
        
    }
    
    
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
        return 'passB';
    }
    
    protected function getSchemaName()
    {
        return 'schemaA';
    }
    
}
/* End of Class */