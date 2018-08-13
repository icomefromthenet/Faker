<?php
namespace Faker\Tests\Engine\Seed\Mock;

use Faker\Components\Engine\Seed\ColumnSeedAbstract;
use Faker\Components\Engine\DB\Builder\FieldBuilder;

class MockColumnBasic extends ColumnSeedAbstract
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
    
}
/* End of Class */