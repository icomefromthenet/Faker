<?php
namespace Faker\Tests\Engine\Common\Compiler;

use Faker\Components\Engine\Common\Compiler\Pass\CacheInjectorPass;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Visitor\DirectedGraphVisitor;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\Tests\Base\AbstractProject;


class CacheInjectorPassTest extends AbstractProject
{


    public function testCacheInjector()
    {
        $pass           = new CacheInjectorPass();
        $visitor        = new DirectedGraphVisitor(new DirectedGraph(),new PathBuilder());
        
        $composite      = $this->getComposite();
        $composite->acceptVisitor($visitor);
        
        $tables         = $composite->getChildren();
        $columnsTable1  = $tables[0]->getChildren();
        $columnsTable2  = $tables[1]->getChildren();
        $columnsTable3  = $tables[2]->getChildren();
        
        $graph          =  $visitor->getResult();
        
        $compiler       = $this->createMock('Faker\Components\Engine\Common\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
        
        # test that the cache been injected into the ColumnNode (producer)
        $this->assertInstanceOf('\Faker\Components\Engine\Common\GeneratorCache',$columnsTable1[0]->getResultCache());
        
        #test that the cache been injected into the ForeignKeyNode (consumer)
        $fk = $columnsTable3[1]->getChildren();
        $this->assertInstanceOf('\Faker\Components\Engine\Common\Composite\ForeignKeyNode',$fk[0]);
        $this->assertInstanceOf('\Faker\Components\Engine\Common\GeneratorCache',$fk[0]->getResultCache());
    }
    
    
    public function testCacheInjectorSilentPreventsInjection()
    {
        $pass           = new CacheInjectorPass();
        $visitor        = new DirectedGraphVisitor(new DirectedGraph(),new PathBuilder());
        
        $composite      = $this->getSilentComposite();
        $composite->acceptVisitor($visitor);
        
        $tables         = $composite->getChildren();
        $columnsTable1  = $tables[0]->getChildren();
        $columnsTable2  = $tables[1]->getChildren();
        $columnsTable3  = $tables[2]->getChildren();
        
        $graph          =  $visitor->getResult();
        
        $compiler       = $this->createMock('Faker\Components\Engine\Common\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
        
        # test that the cache been injected into the ColumnNode (producer)
        $this->assertNull($columnsTable1[0]->getResultCache());
        
        
        #test that the cache been injected into the ForeignKeyNode (consumer)
        $fk = $columnsTable3[1]->getChildren();
        $this->assertNull($fk[0]->getResultCache());
        
    }
    
    
      protected function getSilentComposite()
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
        $fkc1->setOption('silent',true);
        $fkc1->validate();
        
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
        
        $fkc1->validate();
        
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