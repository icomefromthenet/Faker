<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\Table,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Tests\Base\AbstractProject;

class TableTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $rows_generate = 100;
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $table = new Table($id,$parent,$event,$rows_generate);
        
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',$table);
    }
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'table_1';
        $rows_generate = 1;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
 
        $event->expects($this->exactly(4))
              ->method('dispatch')
              ->with($this->logicalOr(
                        $this->stringContains(FormatEvents::onTableStart),
                        $this->stringContains(FormatEvents::onTableEnd),
                        $this->stringContains(FormatEvents::onRowStart),
                        $this->stringContains(FormatEvents::onColumnGenerate),                     
                        $this->stringContains(FormatEvents::onRowEnd)
                        ),
                        $this->isInstanceOf('\Faker\Components\Faker\Formatter\GenerateEvent'));
       
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo($rows_generate),$this->isType('array'))
                ->will($this->returnValue(array('example')));
       
              
        $table = new Table($id,$parent,$event,$rows_generate);
        
        $table->addChild($child_a);
 
        
        $table->generate(1,array());
        
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'table_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $table = new Table($id,$parent,$event,$rows_generate);
             
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->exactly(100))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue(array('example')));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(100))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue(array('example')));
        
       
        $table->addChild($child_a);        
        $table->addChild($child_b);        
        
        $table->generate(1,array());
   
    }
    
    
    public function testToXml()
    {
        $id = 'table_1';
        $rows_generate = 100;
       
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $table = new Table($id,$parent,$event,$rows_generate);
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<column></column>'));
                
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<column></column>'));
          
        $table->addChild($child_a);        
        $table->addChild($child_b);        
        
        $xml = $table->toXml();
        $this->assertContains('<table name="table_1" generate="0">',$xml);
        $this->assertContains('</table>',$xml);    
    }
    
    
    public function testProperties()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate);
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $table->addChild($child_a);        
        $table->addChild($child_b);        
        
        $this->assertEquals($table->getChildren(),array($child_a,$child_b));
        $this->assertSame($table->getEventDispatcher(),$event);
        $this->assertEquals($parent,$table->getParent());
        $this->assertEquals($id,$table->getId());
        $this->assertEquals($rows_generate,$table->getToGenerate());
        
    }
 
 
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Table must have at least 1 column
      */
    public function testValidateWithException()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate);
     
        $table->merge();
        $table->validate();
     
    }
    
    public function testValidate()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate,array('locale' => 'china'));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);        
        
        $table->merge();
        $table->validate();
        
        $this->assertEquals('china',$table->getOption('locale'));
     
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage  Table::Locale not in valid list
      */
    public function testValidateInvalidLocaleOption()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate,array('locale' => 1));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);        
        
        $table->merge();
        $table->validate();
        
    }
    
    public function testValidationNullLocale()
    {
        $id = 'schema_1';
        $rows_generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$rows_generate,array('locale' => null));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);        
        
        $table->merge();
        $table->validate();
        $this->assertEquals('en',$table->getOption('locale'));
        
        
    }
    
}