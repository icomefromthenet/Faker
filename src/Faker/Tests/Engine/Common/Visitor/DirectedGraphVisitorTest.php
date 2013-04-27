<?php
namespace Faker\Tests\Engine\Common\Visitor;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Visitor\DirectedGraphVisitor;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;


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
        
        $fkc1->setOption('foreignTable',$tableA->getId());
        $fkc1->setOption('foreignColumn',$columnA1->getId());
        
        
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
        
        return $schema;
        
    }


    public function testImplementsBaiscVisitor()
    {
        $graph   = new DirectedGraph();
        $path    = new PathBuilder();
        $visitor = new DirectedGraphVisitor($graph,$path);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Visitor\BasicVisitor',$visitor);
        
    }
    
    
    
    public function testVisitorAccept()
    {
        $visitor   = new DirectedGraphVisitor(new DirectedGraph(),new PathBuilder());
        $composite = $this->getComposite();    
        $composite->acceptVisitor($visitor);
        $graph     = $visitor->getResult();
        $tables    = $composite->getChildren();
        
        # have 11 graphNodes
        $this->assertEquals(11,count($graph->getNodes()));
        
        # they are graph nodes which contain reference to composite
        foreach($graph->getNodes() as $node) {
            $this->assertInstanceOf('Faker\Components\Engine\Common\Compiler\Graph\GraphNode',$node);
            $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$node->getValue());
        }
       
        # test tables connected to schema
        $this->assertEquals(3,count($graph->getNode('schema')->getInEdges()));
        
        # Related column has extra in edge
        $this->assertEquals(2,count($graph->getNode('columnA1.tableA.schema')->getInEdges()));
        
        # related tables have extra inEdge (3 = 2 columns + 1 Foriegn key relation )
        $this->assertEquals(3,count($graph->getNode('tableA.schema')->getInEdges()));
        
        # related table has extra outEdge (2 = 1 schema + 1 Foreign table relation)
        $this->assertEquals(2,count($graph->getNode('tableC.schema')->getOutEdges()));
    }
    
    
}
/* End of File */
