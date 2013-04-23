<?php
namespace Faker\Tests\Engine\DB;

use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Tests\Base\AbstractProject;

class BuilderExamplesTest extends AbstractProject
{
    
    
    
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
    
   
    
    
}
/* End of Class */