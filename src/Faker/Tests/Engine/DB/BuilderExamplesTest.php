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
       
        $repo            = $container->getEngineTypeRepository();
        $event           = $container->getEventDispatcher();
        $locale          = $container->getLocaleFactory()->create('en');
        $util            = $container->getEngineUtilities();
        $gen             = $container->getDefaultRandom();
        $conn            = $container->getGeneratorDatabase();
        $loader          = $container->getTemplatingManager()->getLoader();
        $platformFactory = $container->getDBALPlatformFactory();
        $formatterFactory= $container->getFormatterFactory(); 
    
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Builder\SchemaBuilder',SchemaBuilder::create($container,$name,$event,$repo,$locale,$util,$gen,$conn,$loader,$platformFactory,$formatterFactory));
           
    }
    
    public function testExample1()
    {
        $container       = $this->getProject();
        $name            = 'test_db';
        $repo            = $container->getEngineTypeRepository();
        $event           = $container->getEventDispatcher();
        $locale          = $container->getLocaleFactory()->create('en');
        $util            = $container->getEngineUtilities();
        $gen             = $container->getDefaultRandom();
        $conn            = $container->getGeneratorDatabase();
        $loader          = $container->getTemplatingManager()->getLoader();
        $platformFactory = $container->getDBALPlatformFactory();
        $formatterFactory= $container->getFormatterFactory(); 
    
        $builder = new SchemaBuilder($name,$event,$repo,$locale,$util,$gen,$conn,$loader,$platformFactory,$formatterFactory);
        
        $generatorComposite = $builder
                        ->addWriter()
                            ->sqlFormatter()
                                ->singleFileMode(true)
                            ->end()
                        ->end()
                        ->addWriter()
                            ->phpUnitFormatter()
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
        $event           = new EventDispatcher(); 
        
        $event->addListener(FormatEvents::onRowEnd,function(GenerateEvent $generateEvent) {
            var_dump($generateEvent->getValues());
            
        });
        
        $schema = SchemaBuilder::create($container,$name,$event)
                        ->describe()
                            ->addTable('table1')
                                ->toGenerate(1)
                                ->addColumn('column1')
                                    ->dbalType('string')
                                    ->addField()
                                        ->fieldBoolean()
                                            ->value(true)
                                        ->end()`
                                    ->end()
                                    
                                    
                                    ->addField()
                                    ->end()
                                    
                                ->end()
                                ->addColumn()
                                ->end()
                            ->end()
                        ->end()
                        ->addWriter()
                        ->end()
                        
                    ->end();
        
        $this->assertInstanceOf('Faker\Components\Engine\DB\Composite\SchemaNode',$schema);
        
        $values = array();
        
        $schema->generate(1,$values);
        
    }
    
    
}
/* End of Class */