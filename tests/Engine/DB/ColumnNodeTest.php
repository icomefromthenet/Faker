<?php
namespace Faker\Tests\Engine\DB;

use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Tests\Base\AbstractProject;

/**
  *  Test the node implementes Generator and Visitor Interfaces
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ColumnNodeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
        
        $id        = 'columnNode';
        
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new ColumnNode($id,$event,$internal);
        $type->setResultCache($cache);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\ColumnNode',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\GeneratorInterface',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\VisitorInterface',$type);
        $this->assertEquals($cache,$type->getResultCache());
        
    }
    
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
         
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                        ->disableOriginalConstructor()
                        ->getMock(); 
         
        $event->expects($this->at(0))
              ->method('dispatch')
              ->with($this->stringContains(FormatEvents::onColumnStart),$this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));

        $event->expects($this->at(1))
              ->method('dispatch')
              ->with($this->stringContains(FormatEvents::onColumnGenerate),$this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));
        
        $event->expects($this->at(2))
              ->method('dispatch')
              ->with($this->stringContains(FormatEvents::onColumnEnd),$this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));
        
              
        $columnNode = new ColumnNode($id,$event);
        $columnNode->addChild($child_a);
        
        $values = array();
        
        $columnNode->generate(1,$values);
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $columnNode = new ColumnNode($id,$event);
     
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
       
        $columnNode->addChild($child_a);        
        $columnNode->addChild($child_b);        
        
        $values = array();
        
        $columnNode->generate(1,$values);
   
    }
    
    
    public function testGenerateCacheUsed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
        
        $columnNode = new ColumnNode($id,$event);
        
      
        $cache->expects($this->once())
              ->method('add')
              ->with($this->equalTo(null));
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                        ->disableOriginalConstructor()
                        ->getMock();
        $child_b = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                            ->disableOriginalConstructor()
                            ->getMock();
                            
       
        $columnNode->addChild($child_a);        
        $columnNode->addChild($child_b);        
        $columnNode->setResultCache($cache);
        
        $values = array();
        $columnNode->generate(1,$values);
        
    }
    
    public function testVisititorVisitsChildren()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $columnNode = new ColumnNode($id,$event);
     
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
        
        $columnNode->addChild($child_a);        
        $columnNode->addChild($child_b); 
        
        $columnNode->acceptVisitor($visitor);
        
    }
    
    
}
/* End of File */