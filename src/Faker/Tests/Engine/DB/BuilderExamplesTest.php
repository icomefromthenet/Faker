<?php
namespace Faker\Tests\Engine\DB;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Tests\Base\AbstractProject;

class BuilderExamplesTest extends AbstractProject
{
    
    public function testStaticBuilderCreate()
    {
       $container       = $this->getProject(); 
       $name            = 'test_db'; 
       
       $this->assertInstanceOf('Faker\Components\Engine\DB\Builder\SchemaBuilder',SchemaBuilder::create($container,$name));
    }
    
    public function testExample1()
    {
        $container       = $this->getProject();
        $name            = 'test_db';
        $locale          = $container->getLocaleFactory()->create('en');
        $util            = $container->getEngineUtilities();
        $gen             = $container->getDefaultRandom();
    
        $builder = SchemaBuilder::create($container,$name,$locale,$util,$gen);
        
        $generatorComposite = $builder
                        ->addWriter()
                            ->sqlWritter()
                                ->singleFileMode(true)
                            ->end()
                        ->end()
                        ->addWriter()
                            ->phpUnitWritter()
                                ->outputEncoding('utf8')
                                ->outFileFormat('{prefix}_{body}_{suffix}.{ext}')
                            ->end()
                        ->end()
                        ->addWriter()
                            ->customFormatter()
                                ->typeName('sql')
                            ->end()
                        ->end()
                        
                    ->end();
        
        # builder has returned a generator composite and a schema node as root
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$generatorComposite);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\GeneratorInterface',$generatorComposite);
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$generatorComposite);
        
        # check that have 3 formatter nodes
        $this->assertEquals(3,count($generatorComposite->getChildren()));
        
        # make sure all nodes are formatter
        foreach($generatorComposite->getChildren() as $child) {
            $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\FormatterNode',$child);
        }
        
        
    }
    
    public function testExample2()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        
        $schema = SchemaBuilder::create($container,$name)
                        ->describe()
                            ->addTable('table1')
                                ->toGenerate(100)
                                ->addColumn('column2')
                                    ->dbalType('string')
                                    ->addField('column2')
                                        ->fieldAlphaNumeric()
                                            ->format('cccCCCCCCC')
                                        ->end()
                                    ->end()
                                ->end()
                                ->addColumn('column1')
                                    ->dbalType('string')
                                    ->addField()
                                        ->fieldAutoIncrement()
                                            ->incrementByValue(1)
                                            ->startAtValue(1)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->addWriter()
                            ->sqlWritter()
                            ->end()
                        ->end()
                    ->end();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
        
        # assert composite
        
        

        
    }
    
    public function testExample3()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        
        $schema = SchemaBuilder::create($container,$name)
                        ->describe()
                            ->addTable('table1')
                                ->toGenerate(100)
                                ->addColumn('column2')
                                    ->dbalType('string')
                                    ->addField('column2')
                                        ->fieldAlphaNumeric()
                                            ->format('cccCCCCCCC')
                                        ->end()
                                    ->end()
                                ->end()
                                ->addColumn('column1')
                                    ->dbalType('string')
                                    ->addField()
                                        ->fieldAutoIncrement()
                                            ->incrementByValue(1)
                                            ->startAtValue(1)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                             ->addTable('table2')
                                ->toGenerate(5)
                                ->addColumn('id')
                                    ->dbalType('string')
                                    ->addForeignField()
                                        ->foreignTable('table1')
                                        ->foreignColumn('column1')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->addWriter()
                            ->sqlWritter()
                            ->end()
                        ->end()
                    ->end();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
        
        # assert composite
        
    }
    
    
    public function testDatasourceBuilderRetrunedFromSchemaBuilder()
    {
        
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        
        # schema node returns DatasourceBuilder
        $dataSourceBuilder = SchemaBuilder::create($container,$name)
                            ->addDatasource();
                            
                                
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\NodeCollection',$dataSourceBuilder);
        $this->assertInstanceOf('Faker\Components\Engine\DB\Builder\DatasourceBuilder',$dataSourceBuilder);
        
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage bad not found in datasource definition repository unable to create definition
      */
    public function testCustomDatasourceNotfoundInRepo()
    {
        
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        
        # schema node returns DatasourceBuilder
        $dataSourceBuilder = SchemaBuilder::create($container,$name)
                            ->addDatasource();
                            
                                
        $dataSourceBuilder->customDatasource('bad');
        
        
    }
    
    
    public function testPHPDataSourceDefinitionReturnedFromDatasourceBuilder()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        
        # schema node returns DatasourceBuilder
        $dataSourceBuilder = SchemaBuilder::create($container,$name)
                            ->addDatasource();
        
        # test phpsource
        $phpSourceDefinition  = $dataSourceBuilder->createPHPSource();
        $this->assertInstanceOf('Faker\Components\Engine\Common\Datasource\PHPSourceDefinition',$phpSourceDefinition);
        
        #test filsource
        
        
        #test sqlsource
        
    }
    
    
    
}
/* End of Class */