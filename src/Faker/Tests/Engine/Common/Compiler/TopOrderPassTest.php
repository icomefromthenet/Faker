<?php
namespace Faker\Tests\Engine\Common\Compiler;

use Faker\Components\Engine\Common\Compiler\Pass\TopOrderPass;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Visitor\DirectedGraphVisitor;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\Tests\Base\AbstractProject;


class TopOrderRefPassTest extends AbstractProject
{

    
    public function testCircularRefPass()
    {
        $firstOrder = array();
        $finalOrder = array();
        $pass       = new TopOrderPass();
        $composite  = $this->getComposite();
        $graph      = $this->buildDirectedGraph($composite);
        
        foreach($composite->getChildren() as $node) {
            $firstOrder[] = $node->getId();
        }
        
        # assert inital order what expected
        $this->assertEquals(array('tableC','tableB','tableA'),$firstOrder); 
        
        $compiler   = $this->getMock('Faker\Components\Engine\Common\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
         
        foreach($composite->getChildren() as $node) {
            $finalOrder[] = $node->getId();
        }
        
        # assert final order is correct and expected
        $this->assertEquals(array('tableB','tableA','tableC'),$finalOrder); 
        
    }
        
        
    protected function buildDirectedGraph(CompositeInterface $com)
    {
        $visitor = new DirectedGraphVisitor(new DirectedGraph(),new PathBuilder());
        $com->acceptVisitor($visitor);
        return  $visitor->getResult();   
    }
    
    
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
        
        $schema->addChild($tableC);
        $schema->addChild($tableB);
        $schema->addChild($tableA);
        
        return $schema;
        
    }

}
/* End of File */