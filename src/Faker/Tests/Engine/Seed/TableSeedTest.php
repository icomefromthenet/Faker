<?php
namespace Faker\Tests\Engine\Seed;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Composite\DatasourceNode;


use Faker\Tests\Engine\Seed\Mock\MockTableSeedBasic;
use Faker\Tests\Engine\Seed\Mock\MockTableSeedExtras;
use Faker\Tests\Engine\Seed\Mock\MockTableSeedSource;


class TableSeedTest extends AbstractProject
{
    
    public function testSeedBasic()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $tableSeedA     = new MockTableSeedBasic($container,100);
        
        # create test table/schema builder    
        $schemaBuilder = $container->create($name);
            
            $oTableCollection = $schemaBuilder->describe();
        
                $tableSeedA->build($schemaBuilder,array());
        
            // End Describe
            $oTableCollection->end();
        
        // build schema
        $schema = $schemaBuilder->end();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
       
        $aTables     = $schema->getChildren();
        $aColumns    = $aTables[0]->getChildren();
        
        $this->assertEquals(2,count($aColumns));
    }
    
    
    public function testSeedWithDatasource()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $tableSeedA     = new MockTableSeedSource($container,100);
        
        # create test table/schema builder    
        $schemaBuilder = $container->create($name);
            
            $oTableCollection = $schemaBuilder->describe();
        
                $tableSeedA->build($schemaBuilder,array());
        
            // End Describe
            $oTableCollection->end();
        
        // build schema
        $schema = $schemaBuilder->end();
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\DB\\Composite\\SchemaNode',$schema);
        
        $aSchemaChildren = $schema->getChildren();
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$aSchemaChildren[1]);
    }
    
    
     public function testSeedWithExtras()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $tableSeedA     = new MockTableSeedExtras($container,100);
        
        # create test table/schema builder    
        $schemaBuilder = $container->create($name);
            
            $oTableCollection = $schemaBuilder->describe();
        
                $tableSeedA->build($schemaBuilder,array());
        
            // End Describe
            $oTableCollection->end();
        
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
    
    
    
    
    
    
}
/* End of Class */