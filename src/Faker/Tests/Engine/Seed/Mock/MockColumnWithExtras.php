<?php
namespace Faker\Tests\Engine\Seed\Mock;

use Faker\Components\Engine\Seed\ColumnSeedAbstract;
use Faker\Components\Engine\DB\Builder\FieldBuilder;

class MockColumnWithExtras extends ColumnSeedAbstract
{
    
    
    
    
    protected function configureField(FieldBuilder $oFieldBuilder, array $aOptions) 
    {
        
        $oFieldBuilder->fieldAutoIncrement()
                         ->incrementByValue(1)
                         ->startAtValue(1)
                      ->end();
                      
        return null;
    
    }
    
   
    protected function getDBALType()
    {
        return 'string';
    }
    
    protected function getColumnName()
    {
        return 'columna1';
    }
    
    
    protected function getLocaleType()
    {
        return 'en';
    }
    
    protected function getRandomGeneratorType()
    {
        return 'simple';
    }
    
}
/* End of Class */