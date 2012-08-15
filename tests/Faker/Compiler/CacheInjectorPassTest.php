<?php
namespace Faker\Tests\Faker\Compiler;

use Faker\Components\Faker\Compiler\Pass\CacheInjectorPass,
    Faker\Components\Faker\GeneratorCache,
    Faker\Components\Faker\Visitor\DirectedGraphVisitor,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Compiler\Graph\DirectedGraph,
    Faker\Tests\Base\AbstractProject;


class CacheInjectorPassTest extends AbstractProject
{


    public function testCacheInjector()
    {
        $composite = $this->getComposite();
        $graph = $this->buildDirectedGraph($composite);
        $compiler = $this->getMock('Faker\Components\Faker\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        
        $pass = new CacheInjectorPass();
        
        $pass->process($composite,$compiler);
        
        $tables = $composite->getChildren();
        
        $columns_table1  = $tables[0]->getChildren();
        $columns_table2  = $tables[1]->getChildren();
        $columns_table3 = $tables[2]->getChildren();
        
        # test that the cache been injected into the key generator
        $this->assertTrue($columns_table1[0]->getUseCache());
        $this->assertInstanceOf('\Faker\Components\Faker\GeneratorCache',$columns_table1[0]->getGeneratorCache());
        
        #test that the cache been injected into the key consumer
        $fk = $columns_table2[0]->getChildren();
        $this->assertTrue($fk[0]->getUseCache());
        $this->assertInstanceOf('\Faker\Components\Faker\GeneratorCache',$fk[0]->getGeneratorCache());
        
        
        #test that fk has been set to skip the cache and the related column has no cache
        $this->assertFalse($columns_table2[0]->getUseCache());
        $this->assertNull($columns_table2[0]->getGeneratorCache());
        $fk = $columns_table3[0]->getChildren();
        $this->assertFalse($fk[0]->getUseCache());
        
    }
    
    protected function buildDirectedGraph(CompositeInterface $com)
    {
        $visitor = new DirectedGraphVisitor(new DirectedGraph());
        $com->acceptVisitor($visitor);
        return  $visitor->getDirectedGraph();   
    }
        
        
    protected function getComposite()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $builder->addSchema('schema1',array('name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'name' => 'table1'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                    ->addTable('table2',array('generate' => 100,'name' => 'table2'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addForeignKey('table2.columnA',array('foreignTable'=> 'table1','foreignColumn' => 'columnA','name' => 'table2.columnA'))->end()
                        ->end()
                    ->end()
                    ->addTable('table3',array('generate' => 100,'name' => 'table3'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addForeignKey('table3.columnA',array('foreignTable'=> 'table2','foreignColumn' => 'columnA','useCache' => false))->end()
                        ->end()
                    ->end()
                ->end();
        
        $builder->merge();
        
        return $builder->getSchema();
    }
    
}
/* End of File */