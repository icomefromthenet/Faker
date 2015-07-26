<?php
namespace Faker\Tests\Engine\Seed;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Composite\DatasourceNode;


use Faker\Tests\Engine\Seed\Mock\MockSchemaSeed;
use Faker\Tests\Engine\Seed\Mock\MockSchemaSeedExtras;


class SchemaSeedTest extends AbstractProject
{
    
    public function testSeedBasic()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $schemaSeed     = new MockSchemaSeed($container);
        
        # create test table/schema builder    
        $schema = $schemaSeed->build(array ());
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
       
        $aTables     = $schema->getChildren();
        
        $this->assertEquals(2,count($aTables));
    }
    
    
    public function testSeedWithWritterAndSource() 
    {
        
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $schemaSeed     = new MockSchemaSeedExtras($container);
        
        # create test table/schema builder    
        $schema = $schemaSeed->build(array ());
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
       
        $aTables     = $schema->getChildren();
        
        $this->assertEquals(4,count($aTables));    
        
    }
    
    
    
    
}
/* End of Class */