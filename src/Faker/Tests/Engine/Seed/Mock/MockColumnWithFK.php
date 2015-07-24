<?php
namespace Faker\Tests\Engine\Seed\Mock;

use Faker\Components\Engine\Seed\ColumnSeedAbstract;
use Faker\Components\Engine\DB\Builder\FieldBuilder;
use Faker\Components\Engine\DB\Builder\ForeignKeyBuilder;

class MockColumnWithFK extends ColumnSeedAbstract
{
    
    protected function foreignKeyNameX(ForeignKeyBuilder $oKeyBuilder,array $aOptions)
    {
        $oKeyBuilder->foreignTable('table2');
        $oKeyBuilder->foreignColumn('column2');
        $oKeyBuilder->silent(false);
    }
    
    
    protected function configureField(FieldBuilder $oFieldBuilder, array $aOptions) 
    {
      
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