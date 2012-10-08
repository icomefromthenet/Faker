<?php
namespace Faker\Tests\Faker\Visitor;

use Faker\Components\Faker\Visitor\DirectedGraphVisitor,
    Faker\Components\Faker\Compiler\Graph\DirectedGraph,
    Faker\Components\Faker\Composite\CompositeFinder,
    Faker\Tests\Base\AbstractProject;

class GraphBuildingTest extends AbstractProject
{

    public function testInjectionGoodIndex()
    {
        $composite = $this->getComposite();    
        $visitor   = new DirectedGraphVisitor(new DirectedGraph());
        $composite->acceptVisitor($visitor);
        
        $graph = $visitor->getDirectedGraph();
        
        # have 7 graphNodes
        $this->assertEquals(7,count($graph->getNodes()));
        foreach($graph->getNodes() as $node) {
            $this->assertInstanceOf('Faker\Components\Faker\Compiler\Graph\GraphNode',$node);
        }
       
        # test tables connected to schema
        $this->assertEquals(2,count($graph->getNode('schema1')->getInEdges()));
        
        # check the relations between table1 and table 2
        $finder = new CompositeFinder();
        
        $table1 = $graph->getNode($finder->set($composite)
                                         ->table('table1')
                                         ->get()
                                         ->getId()
                                  );
        
        $table2 = $graph->getNode($finder->set($composite)
                                         ->table('table2')
                                         ->get()
                                         ->getId()
                );
        
        $this->assertEquals(1,count($table1->getOutEdges()));
        $this->assertEquals(2,count($table1->getInEdges()));
        
        # check if relation from table2 to table1
        
        $this->assertEquals(2,count($table2->getOutEdges()));
        $this->assertEquals(1,count($table2->getInEdges()));
        
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
                            ->addForeignKey('table2.columnA',array('foreignTable'=> 'table1','foreignColumn' => 'columnA','name' =>'table2.columnA'))
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $builder->merge();
        return $builder->getSchema();
    }
    
}
/* End of File */