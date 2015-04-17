<?php
namespace Faker\Tests\Engine\DB;

use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Tests\Base\AbstractProject;

/**
  *  Test the node implementes Generator and Visitor Interfaces
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TableNodeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
        $toGenerate= 1;
        
        $id        = 'tableNode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new TableNode($id,$event,$internal);
        $type->setResultCache($cache);
        $type->setRowsToGenerate($toGenerate);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\TableNode',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\GeneratorInterface',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\VisitorInterface',$type);
        $this->assertEquals($cache,$type->getResultCache());
        $this->assertEquals($toGenerate,$type->getRowsToGenerate());
        
    }
    
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'schema_1';
        $toGenerate= 1;
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();

        
        $child = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $event->expects($this->at(0))
              ->method('dispatch')
              ->with($this->stringContains(FormatEvents::onTableStart),$this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));

        $event->expects($this->at(1))
              ->method('dispatch')
              ->with($this->stringContains(FormatEvents::onRowStart),$this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));
        
        $event->expects($this->at(2))
              ->method('dispatch')
              ->with($this->stringContains(FormatEvents::onRowEnd),$this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));
        
        $event->expects($this->at(3))
              ->method('dispatch')
              ->with($this->stringContains(FormatEvents::onTableEnd),$this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));
        
        $tableNode = new TableNode($id,$event);
        $tableNode->addChild($child);
        $tableNode->setRowsToGenerate($toGenerate);
        
        $values = array();
        $tableNode->generate(1,$values);
        
        
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'schema_1';
        $toGenerate= 1;
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $tableNode = new TableNode($id,$event);
        $tableNode->setRowsToGenerate($toGenerate);
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'));
       
        $child_b = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                            ->disableOriginalConstructor()
                            ->getMock();
                            
        $child_b->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'));
       
        $tableNode->addChild($child_a);        
        $tableNode->addChild($child_b);        
        $values = array();
        $tableNode->generate(1,$values);
   
    }
    
    public function testChildrenGenerateCalledMany()
    {
        $id = 'schema_1';
        $toGenerate= 5;
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $tableNode = new TableNode($id,$event);
        $tableNode->setRowsToGenerate($toGenerate);
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $child_a->expects($this->exactly(5))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'));
       
        $child_b = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                            ->disableOriginalConstructor()
                            ->getMock();
                            
        $child_b->expects($this->exactly(5))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'));
       
        $tableNode->addChild($child_a);        
        $tableNode->addChild($child_b);        
        $values = array();
        $tableNode->generate(1,$values);
   
    }
    
    
    public function testVisititorVisitsChildren()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $tableNode = new TableNode($id,$event);
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $child_a->expects($this->once())
                ->method('acceptVisitor')
                ->with($this->isInstanceOf('Faker\Components\Engine\Common\Visitor\BasicVisitor'));
       
        $child_b = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                            ->disableOriginalConstructor()
                            ->getMock();
                            
        $child_b->expects($this->once())
                ->method('acceptVisitor')
                ->with($this->isInstanceOf('Faker\Components\Engine\Common\Visitor\BasicVisitor'));
       
        
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\BasicVisitor')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $tableNode->addChild($child_a);        
        $tableNode->addChild($child_b); 
        
        $tableNode->acceptVisitor($visitor);
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage rows to generate must be an integer
      */
    public function testValidationFailsRowsToGenerateBadType()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
        $toGenerate= '1';
        
        $id        = 'tableNode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new TableNode($id,$event,$internal);
        $type->setResultCache($cache);
        $type->setRowsToGenerate($toGenerate);
        
        $type->validate();
    }
    
     /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage rows to generate must be > 0
      */
    public function testValidationFailsRowsToGenerateBadRange()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
        $toGenerate= 0;
        
        $id        = 'tableNode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new TableNode($id,$event,$internal);
        $type->setResultCache($cache);
        $type->setRowsToGenerate($toGenerate);
        
        $type->validate();
    }
    
    
    public function testDatasourceIntFlushCalled()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $tableNode = new TableNode($id,$event);
     
        $dSource = $this->getMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        
        $dSource->expects($this->once())
                ->method('fetchOne');
                
        $dSource->expects($this->once())
                ->method('flushSource');
        
        $node = new \Faker\Components\Engine\Common\Composite\DatasourceNode('source1',$event,$dSource);
        
        $tableNode->addChild($node);
        $tableNode->setRowsToGenerate(1);        
        $values = array();
        $tableNode->generate(1,$values);        
    }
    
    
    
}
/* End of File */