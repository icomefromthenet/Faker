<?php
namespace Faker\Tests\Faker\Compiler;

use Faker\Components\Faker\Compiler\Pass\CacheInjectorPass,
    Faker\Components\Faker\GeneratorCache,
    Faker\Tests\Base\AbstractProject;


class CacheInjectorPassTest extends AbstractProject
{


    public function testCacheInjector()
    {
        $pass = new CacheInjectorPass();
        $composite = $this->getComposite();
        $pass->process($composite);
        
        $tables = $composite->getChildren();
        
        $columns_table1 = $tables[0]->getChildren();
        $columns_table2 = $tables[1]->getChildren();
        
        # test that the cache been injected into the key generator
        $this->assertTrue($columns_table1[0]->getUseCache());
        $this->assertInstanceOf('\Faker\Components\Faker\GeneratorCache',$columns_table1[0]->getGeneratorCache());
        
        #test that the cache been injected into the key consumer
        $fk = $columns_table2[0]->getChildren();
        $this->assertTrue($fk[0]->getUseCache());
        $this->assertInstanceOf('\Faker\Components\Faker\GeneratorCache',$fk[0]->getGeneratorCache());
    }
    
   
        
        
    protected function getComposite()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric')
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                    ->addTable('table2',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addForeignKey('table2.columnA',array('foreignTable'=> 'table1','foreignColumn' => 'columnA'))->end()
                        ->end()
                    ->end()
                ->end();
        
        $builder->merge();
        return $builder->getSchema();
    }
    
}
/* End of File */