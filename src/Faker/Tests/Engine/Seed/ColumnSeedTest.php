<?php
namespace Faker\Tests\Engine\Seed;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Composite\DatasourceNode;


use Faker\Tests\Engine\Seed\Mock\MockColumnBasic;
use Faker\Tests\Engine\Seed\Mock\MockColumnWithExtras;
use Faker\Tests\Engine\Seed\Mock\MockColumnWithFK;

class ColumnSeedTest extends AbstractProject
{
    
    
    
   
    public function testSeedBasic()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $columnSeedA     = new MockColumnBasic($container);
        
        # create test table/schema builder    
        $schemaBuilder = $container->create($name);
            $tableCollection = $schemaBuilder->describe();
                # create new table
                $tableBuilder = $tableCollection->addTable('table1')->toGenerate(100);
                    # create new Bsic column
                    $columnSeedA->build($tableBuilder,array());
                // build table
                $tableBuilder->end();
            // End Describe
            $tableCollection->end();
        
        // build schema
        $schema = $schemaBuilder->end();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
        
    }
    
    
    public function testSeedWithExtras()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $columnSeedA     = new MockColumnWithExtras($container);
        
        # create test table/schema builder    
        $schemaBuilder = $container->create($name);
            $tableCollection = $schemaBuilder->describe();
                # create new table
                $tableBuilder = $tableCollection->addTable('table1')->toGenerate(100);
                    # create new Bsic column
                    $columnSeedA->build($tableBuilder,array());
                // build table
                $tableBuilder->end();
            // End Describe
            $tableCollection->end();
        
        // build schema
        $schema = $schemaBuilder->end();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
        
        $aTables     = $schema->getChildren();
        $aColumns    = $aTables[0]->getChildren();
        $aTestColumn = $aColumns[0];
        $aTestFields = $aTestColumn->getChildren();
        $aTestField  = $aTestFields[0]-> getType();
        
        $this->assertInstanceOf('PHPStats\Generator\SimpleRandom',$aTestField->getGenerator());
        $this->assertInstanceOf('Faker\Locale\EnglishLocale',$aTestField->getLocale());
        $this->assertInstanceOf('Doctrine\DBAL\Types\StringType',$aTestColumn->getDBALType());
        
    }
    
     public function testSeedWithForeignKey()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $columnSeedA     = new MockColumnWithFK($container);
        
        # create test table/schema builder    
        $schemaBuilder = $container->create($name);
            $tableCollection = $schemaBuilder->describe();
                # create new table
                $tableBuilder = $tableCollection->addTable('table1')->toGenerate(100);
                    # create new Bsic column
                    $columnSeedA->build($tableBuilder,array());
                // build table
                $tableBuilder->end();
                
                $tableCollection
                    ->addTable('table2')
                    ->toGenerate(100)
                     ->addColumn('column2')
                        ->dbalType('string')
                            ->addField('column2')
                                ->fieldAlphaNumeric()
                                    ->format('cccCCCCCCC')
                                ->end()
                            ->end()
                        ->end()
                    ->end();
                
            // End Describe
            $tableCollection->end();
        
        // build schema
        $schema = $schemaBuilder->end();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
        
        $aTables     = $schema->getChildren();
        $aColumns    = $aTables[1]->getChildren();
        $aTestColumn = $aColumns[0];
        $aTestFields = $aTestColumn->getChildren();
        $aTestField  = $aTestFields[0];
    
        // have we a fk node for this column children
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\ForeignKeyNode',$aTestField);
        
        
    }
    
    
    
    
}
/* End of Class */