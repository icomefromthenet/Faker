<?php
namespace Faker\Tests\Engine\Common\Visitor;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Visitor\DirectedGraphVisitor;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;

class DBALVisitorTest extends AbstractProject
{

    protected function getComposite()
    {
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema    = new SchemaNode('schema',$event);
        
        $tableA    = new TableNode('tableA',$event);
        $columnA1  = new ColumnNode('columnA1',$event);
        $columnA2  = new ColumnNode('columnA2',$event);
        
        $tableB    = new TableNode('tableB',$event);
        $columnB1  = new ColumnNode('columnB1',$event);
        $columnB2  = new ColumnNode('columnB2',$event);
        
        $tableC    = new TableNode('tableC',$event);
        $columnC1  = new ColumnNode('columnC1',$event);
        $columnC2  = new ColumnNode('columnC2',$event);
        $fkc1      = new ForeignKeyNode('fkc1',$event);
        
        
        $columnC2->addChild($fkc1);
        $tableC->addChild($columnC1);
        $tableC->addChild($columnC2);
        
        $tableB->addChild($columnB1);
        $tableB->addChild($columnB2);
        
        $tableA->addChild($columnA1);
        $tableA->addChild($columnA2);
        
        $schema->addChild($tableA);
        $schema->addChild($tableB);
        $schema->addChild($tableC);
        
    }


    public function testImplementsBaiscVisitor()
    {
        $graph   = new DirectedGraph();
        $visitor = new DirectedGraphVisitor($graph);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Visitor\BasicVisitor',$visitor);
        
    }
    
    
    public function testProperties()
    {
        $graph   = new DirectedGraph();
        $visitor = new DirectedGraphVisitor($graph);
        
        $this->assertEquals($valueMapper,$visitor->getResult($graph));
    }

    
    public function testVisitorAccept()
    {
        $composite = $this->getComposite();    
        
        $visitor   = new DirectedGraphVisitor(new DirectedGraph());
        
        $composite->acceptVisitor($visitor);
        
        $graph = $visitor->getResult();
        
        # have 7 graphNodes
        $this->assertEquals(7,count($graph->getNodes()));
        
        foreach($graph->getNodes() as $node) {
            $this->assertInstanceOf('Faker\Components\Engine\Original\Compiler\Graph\GraphNode',$node);
        }
       
        # test tables connected to schema
        $this->assertEquals(2,count($graph->getNode('schema1')->getInEdges()));
        
        # check the relations between table1 and table 2
        $finder = new CompositeFinder();
        
        $table1 = $graph->getNode('');
        $table2 = $graph->getNode('');
        
        $this->assertEquals(1,count($table1->getOutEdges()));
        $this->assertEquals(2,count($table1->getInEdges()));
        
        # check if relation from table2 to table1
        
        $this->assertEquals(2,count($table2->getOutEdges()));
        $this->assertEquals(1,count($table2->getInEdges()));
        
    }
    
    
}
/* End of File */