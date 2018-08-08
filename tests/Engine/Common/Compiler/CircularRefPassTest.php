<?php
namespace Faker\Tests\Engine\Common\Compiler;

use Faker\Components\Engine\Common\Compiler\Pass\CircularRefPass;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Visitor\DirectedGraphVisitor;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\Tests\Base\AbstractProject;


class CircularRefPassTest extends AbstractProject
{


    /**
      *  @expectedException \Faker\Components\Engine\Common\Compiler\Pass\CircularReferenceException
      *  @expectedExceptionMessage Circular reference detected "table1.schema1", path: "table1.schema1 -> table2.schema1 -> table1.schema1"
      */
    public function testCircularRefPassBadRef()
    {
        $pass = new CircularRefPass();
        
        $composite = $this->getComposite();
        $graph = $this->buildDirectedGraph($composite);

        $compiler = $this->createMock('Faker\Components\Engine\Common\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Compiler\Pass\CircularReferenceException
      *  @expectedExceptionMessage path: "table2.schema1 -> table2.schema1
      */
    public function testCircularRefPassBadSelfRef()
    {
        $pass = new CircularRefPass();
        
        $composite = $this->getCompositeCircularRefOnSelf();
        $graph = $this->buildDirectedGraph($composite);

        $compiler = $this->createMock('Faker\Components\Engine\Common\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
    }
    
    public function testCircularRefPass()
    {
        $pass = new CircularRefPass();
        $composite = $this->getCompositeNoCircularRef();
        $graph = $this->buildDirectedGraph($composite);
        
        $compiler = $this->createMock('Faker\Components\Engine\Common\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
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
        $schema    = new SchemaNode('schema1',$event);
        
        $table1    = new TableNode('table1',$event);
        $column1A  = new ColumnNode('column1A',$event); 
        $column1B  = new ColumnNode('column1B',$event);                                     
        
        $fk1       = new ForeignKeyNode('fk1',$event);
        $fk1->setOption('foreignTable', 'table2');
        $fk1->setOption('foreignColumn', 'column2B');


        $table2     = new TableNode('table2',$event);
        $column2A   = new ColumnNode('column2A',$event);
        $column2B   = new ColumnNode('column2B',$event);
        $fk2        = new ForeignKeyNode('fk2',$event);
        $fk2->setOption('foreignTable','table1');
        $fk2->setOption('foreignColumn','column1B');
        
        $column2B->addChild($fk2);
        $table2->addChild($column2A);
        $table2->addChild($column2B);
        
        $column1A->addChild($fk1);
        $table1->addChild($column1A);
        $table1->addChild($column1B);
        
        $schema->addChild($table1);
        $schema->addChild($table2);
                
        return $schema;
    }
    
    protected function getCompositeCircularRefOnSelf()
    {
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema    = new SchemaNode('schema1',$event);
        
        $table2     = new TableNode('table2',$event);
        $column2A   = new ColumnNode('column2A',$event);
        $column2B   = new ColumnNode('column2B',$event);

        $fk2        = new ForeignKeyNode('fk2',$event);
        $fk2->setOption('foreignTable','table2');
        $fk2->setOption('foreignColumn','column2B');

        $column2B->addChild($fk2);
        $table2->addChild($column2A);
        $table2->addChild($column2B);
        
        $schema->addChild($table2);
                
        return $schema;
    }
    
    protected function getCompositeNoCircularRef()
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

}
/* End of File */