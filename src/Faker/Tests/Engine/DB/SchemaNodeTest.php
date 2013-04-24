<?php
namespace Faker\Tests\Engine\DB;

use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Tests\Base\AbstractProject;

/**
  *  Test the node implementes Generator and Visitor Interfaces
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SchemaNodeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
        
        $id        = 'schemaNode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new SchemaNode($id,$event,$internal);
        $type->setResultCache($cache);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\SchemaNode',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\GeneratorInterface',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\VisitorInterface',$type);
        $this->assertEquals($cache,$type->getResultCache());
        
    }
    
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();

        $event->expects($this->exactly(2))
              ->method('dispatch')
              ->with(
                     $this->logicalOr(
                                      $this->stringContains(FormatEvents::onSchemaStart),
                                      $this->stringContains(FormatEvents::onSchemaEnd)
                                      ),
                     $this->isInstanceOf('\Faker\Components\Engine\Common\Formatter\GenerateEvent'));
              
        $schema = new SchemaNode($id,$event);
        
        $values = array();
        $schema->generate(1,$values);
        
        
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
     
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
       
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        
        $values = array();
        $schema->generate(1,$values);
   
    }
    
    
    public function testVisititorVisitsChildren()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
     
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
        
        $schema->addChild($child_a);        
        $schema->addChild($child_b); 
        
        $schema->acceptVisitor($visitor);
        
    }
    
    
}
/* End of File */