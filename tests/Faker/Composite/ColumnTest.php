<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Doctrine\DBAL\Types\Type as ColumnType,
    Faker\Tests\Base\AbstractProject;
    
class ColumnTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
      
        $column = new Column($id,$parent,$event,$column_type);
        
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',$column);
    
    }
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
      
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
 
        $event->expects($this->exactly(3))
              ->method('dispatch')
              ->with($this->logicalOr(
                        $this->stringContains(FormatEvents::onColumnStart),
                        $this->stringContains(FormatEvents::onColumnGenerate),
                        $this->stringContains(FormatEvents::onColumnEnd)
                        ),
                        $this->isInstanceOf('\Faker\Components\Faker\Formatter\GenerateEvent'));
       
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'))
                ->will($this->returnValue('example'));
       
              
        $column = new Column($id,$parent,$event,$column_type);
        $column->addChild($child_a);
        $column->generate(1,array());
        
    }
    
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=>null));
             
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->exactly(1))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $column->validate();
        
        $column->generate(100,array());
        
        # assert default locale
        $this->assertEquals($column->getOption('locale'),'en');
   
    }
    
    public function testConfigurationParsed()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $child_a->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $column->validate();
        
        $this->assertEquals($column->getOption('locale'),'china');
   
    }
    
    
    public function testToXml()
    {
        $id = 'column_1';
        $rows_generate = 100;
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        $column_type->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('type'));
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $column = new Column($id,$parent,$event,$column_type);
     
        
        $xml = $column->toXml();
        $this->assertContains('<column name="column_1" type="type">',$xml );
        $this->assertContains('</column>',$xml);
    
    }
    
    
    public function testProperties()
    {
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $column = new Column($id,$parent,$event,$column_type);
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $this->assertEquals($column->getChildren(),array($child_a,$child_b));
        $this->assertSame($column->getEventDispatcher(),$event);
        $this->assertEquals($parent,$column->getParent());
        $this->assertEquals($id,$column->getId());
        $this->assertSame($column_type,$column->getColumnType());
    }
    
}