<?php
namespace Faker\Tests\Engine\DB;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Composite\DatasourceNode;

class BuilderExamplesTest extends AbstractProject
{
    
    public function testStaticBuilderCreate()
    {
      $container       = $this->getProject(); 
      $name            = 'test_db'; 
       
      $this->assertInstanceOf('Faker\Components\Engine\DB\Builder\SchemaBuilder',$container->create($name));
    }
    
    public function testExample1()
    {
        $container       = $this->getProject();
        $name            = 'test_db';
        $locale          = $container->getLocaleFactory()->create('en');
        $util            = $container->getEngineUtilities();
        $gen             = $container->getDefaultRandom();
    
        $builder = $container->create($name,$locale,$util,$gen);
        
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
        
        $schema = $container->create($name)
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
        
        $schema = $container->create($name)
                        ->describe()
                            ->addTable('table1')
                                ->toGenerate(100)
                                ->addColumn('column2')
                                    ->dbalType('string')
                                    ->addField('column2')
                                        ->fieldAlphaNumeric()
                                            ->format('cccCCCCCCC')
                                        ->endAlphaNumericField()
                                    ->endField()
                                ->endColumn()
                                ->addColumn('column1')
                                    ->dbalType('string')
                                    ->addField()
                                        ->fieldAutoIncrement()
                                            ->incrementByValue(1)
                                            ->startAtValue(1)
                                        ->endAutoIncrementField()
                                    ->endField()
                                ->endColumn()
                            ->endTable()
                             ->addTable('table2')
                                ->toGenerate(5)
                                ->addColumn('id')
                                    ->dbalType('string')
                                    ->addForeignField()
                                        ->foreignTable('table1')
                                        ->foreignColumn('column1')
                                    ->endForeignField()
                                ->endColumn()
                            ->endTable()
                        ->endDescribe()
                        ->addWriter()
                            ->sqlWriter()
                            ->endSqlWriter()
                        ->endWriter()
                    ->endSchema();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
        
        # assert composite
        
    }
    
    
    public function testDatasourceBuilderRetrunedFromSchemaBuilder()
    {
        
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        
        # schema node returns DatasourceBuilder
        $dataSourceBuilder = $container->create($name)
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
        $dataSourceBuilder = $container->create($name)
                            ->addDatasource();
                            
                                
        $dataSourceBuilder->customDatasource('bad');
        
        
    }
    
    public function testBeforeAfterEventHealpers()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        $that            = $this;
        $schema = $container->create($name);
    
        
        $schema->onGenerateStart(function() use ($that){
            $that->assertTrue(true);
        });
        
        $schema->onGenerateEnd(function() use ($that){
            $that->assertTrue(true);
        });
        
        $event->dispatch(FormatEvents::onSchemaStart);
        $event->dispatch(FormatEvents::onSchemaEnd);
    }
    
    public function testPHPDataSourceDefinitionReturnedFromDatasourceBuilder()
    {
        $container       = $this->getProject(); 
        $name            = 'test_db'; 
        $event           = $container->getEventDispatcher();
        
        # schema node returns DatasourceBuilder
        $dataSourceBuilder = $container->create($name)
                            ->addDatasource();
        
        # test phpsource
        $phpSourceDefinition  = $dataSourceBuilder->createPHPSource();
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Datasource\\PHPSourceDefinition',$phpSourceDefinition);
        
        #test filsource
        
        #test sqlsource
        
    }
    
    public function testBuildInjectsDatasources()
    {
        $container       = $this->getProject(); 
        $name            = 'SchemaA';
        $finder          = new CompositeFinder();
        
        $schema = $container->create($name)
                        ->describe()
                            ->addTable('table1')
                                ->toGenerate(100)
                                ->addColumn('column2')
                                    ->dbalType('string')
                                    ->addField('column2')
                                        ->fieldFromSource()
                                            ->useTemplateString('{valueA}')
                                            ->useDatasource('mySourceA')
                                        ->endFromSourceField()
                                    ->end()
                                ->end()
                                ->addColumn('column1')
                                    ->dbalType('string')
                                    ->addField()
                                        ->fieldAutoIncrement()
                                            ->incrementByValue(1)
                                            ->startAtValue(1)
                                        ->endAutoIncrementField()
                                    ->endField()
                                    
                                ->endColumn()
                            ->endTable()
                             ->addTable('table2')
                                ->toGenerate(5)
                                ->addColumn('id')
                                    ->dbalType('string')
                                    ->addForeignField()
                                        ->foreignTable('table1')
                                        ->foreignColumn('column1')
                                    ->endForeignField()
                                ->endColumn()
                            ->endTable()
                        ->endDescribe()
                        ->addDatasource()
                            ->createPHPSource()
                                ->setDatasourceName('mySourceA')
                                ->setDataFromClosure(function(){
                                    $data = new \ArrayIterator();
                                    $data[] = array('valueA' =>1);
                                    $data[] = array('valueA' =>2);
                                    return $data;
                                })
                            ->endPHPSource()
                        ->endDatasource()
                        ->addWriter()
                            ->sqlWritter()
                            ->endSqlWriter()
                        ->endWriter()
                    ->endSchema();
        
        
        
        
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$schema->getChildren()[1]);
        
        # vefiy the fromSource TypeNode was created and contains a FromSource Type
        $typeNode = $finder->set($schema)->table('table1')->column('column2')->type('FromSource')->get();
        $fromSourceType = $typeNode ->getType();
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Type\\FromSource',$fromSourceType);
        
        # verify the source was added
        $this->assertEquals('mySourceA',$fromSourceType->getOption('source'));
        
        # verify the TypeNode that contains the FromSource Type has the datasource as a child
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$typeNode->getChildren()[0]);
        
        # veify the table has a datasource node as a child
        $tableNode = $finder->set($schema)->table('table1')->get();
        $foundChildDatasource = false;
        foreach($tableNode->getChildren() as $child) {
            if($child instanceof DatasourceNode) {
                $foundChildDatasource = true;
                break;
            }
        }
        $this->assertTrue($foundChildDatasource,'Unable to find a datasource assigned to the table that contains a fromSource type');
    }
    
    
    
    public function testHowManyRows()
    {
        
        $container       = $this->getProject(); 
        $name            = 'SchemaA';
        
        $container->clearBuilderCollection();
        
        $schema = $container->addPass('PASS1',$name)
                        ->describe()
                            ->addTable('table1')
                                ->toGenerate(100)
                                ->addColumn('column2')
                                    ->dbalType('string')
                                    ->addField('column2')
                                        ->fieldFromSource()
                                            ->useTemplateString('{valueA}')
                                            ->useDatasource('mySourceA')
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
                        ->addDatasource()
                            ->createPHPSource()
                                ->setDatasourceName('mySourceA')
                                ->setDataFromClosure(function(){
                                    $data = new \ArrayIterator();
                                    $data[] = array('valueA' =>1);
                                    $data[] = array('valueA' =>2);
                                    return $data;
                                })
                            ->end()
                        ->end()
                        ->addWriter()
                            ->sqlWritter()
                            ->end()
                        ->end()
                    ->end();
        
        
        $this->assertEquals(105,$container->howManyRows());
        
    }
    
    /**
     * @expectedException Faker\Exception
     * @expectedExceptionMessage The PASS1 passname is already set in this project
     * 
     */ 
    public function testExceptionThrownOnDuplicatePassName()
    {
        
        $container       = $this->getProject(); 
        $name            = 'SchemaA';
        
        $container->clearBuilderCollection();
        $container->addPass('PASS1',$name);
        $container->addPass('PASS1','schema2');
        
    }
    
    
    /**
     * @expectedException Faker\Exception
     * @expectedExceptionMessage The passname can not be empty value
     * 
     */ 
    public function testExceptionThrownOnEmptyPassName()
    {
        
        $container       = $this->getProject(); 
        $name            = 'SchemaA';
        
        $container->clearBuilderCollection();
        $container->addPass('PASS1',$name);
        $container->addPass('','schema2');
        
    }
}
/* End of Class */