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
        $generate = 100;
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $table = new Table($id,$parent,$event,$generate,array('name' => $id,'generate' => $generate));
        
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',$table);
    }
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'table_1';
        $generate = 1;
       
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
                ->with($this->equalTo($generate),$this->isType('array'))
                ->will($this->returnValue(array('example')));
       
              
        $table = new Table($id,$parent,$event,$generate,array('name' => $id,'generate' => $generate));
        
        $table->addChild($child_a);
 
        
        $table->generate(1,array());
        
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $table = new Table($id,$parent,$event,$generate,array('name' =>$id,'generate' => $generate));
             
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
        $generate = 100;
       
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $table = new Table($id,$parent,$event,$generate,array('name' => $id,'generate' => $generate));
     
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
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('name' => $id,'generate' => $generate));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $table->addChild($child_a);        
        $table->addChild($child_b);        
        
        $this->assertEquals($table->getChildren(),array($child_a,$child_b));
        $this->assertSame($table->getEventDispatcher(),$event);
        $this->assertEquals($parent,$table->getParent());
        $this->assertEquals($id,$table->getId());
        $this->assertEquals($generate,$table->getToGenerate());
        
    }
 
 
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Table must have at least 1 column
      */
    public function testValidateWithException()
    {
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('name' => $id,'generate' => $generate));
     
        $table->merge();
        $table->validate();
     
    }
    
    public function testValidate()
    {
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => 'china','name' => $id,'generate' => $generate));
     
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
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => 1,'name' => $id,'generate' => $generate));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);        
        
        $table->merge();
        $table->validate();
        
    }
    
    public function testValidationNullLocale()
    {
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => null,'name' => $id,'generate' => $generate));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);        
        
        $table->merge();
        $table->validate();
        $this->assertEquals('en',$table->getOption('locale'));
        
        
    }
    
    public function testNoDefaultsSetForGenerator()
    {
        
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => null,'name' => $id,'generate' => $generate));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);
        
        $table->merge();
        $this->assertFalse($table->hasOption('randomGenerator'));
        $this->assertFalse($table->hasOption('generatorSeed'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": Table::generatorSeed must be an integer
      */
    public function testNotIntergerRefusedForGeneratorSeed()
    {
        
       $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => null,'generate' => $generate , 'name' => $id));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);
        
        $table->setOption('generatorSeed','a non integer');
        
        $table->merge();
        
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": Table::generatorSeed must be an integer
      */
    public function testNullNotAcceptedGeneratorSeed()
    {
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => null,'generate' => $generate,'name' => $id));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);
        
        $table->setOption('generatorSeed',null);
        
        $table->merge();
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": Table::generatorSeed must be an integer
      */
    public function testEmptyStringNotAcceptedGeneratorSeed()
    {
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => null,'generate' => $generate,'name' => $id));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);
        
        $table->setOption('generatorSeed','');
        
        $table->merge();
        
        
    }
    
    
    public function testGeneratorOptionsAccepted()
    {
        
        $id = 'table_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $table = new Table($id,$parent,$event,$generate,array('locale' => null,'generate' => $generate,'name' => $id));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $table->addChild($child_a);
        
        $table->setOption('generatorSeed',100);
        $table->setOption('randomGenerator','srand');
        
        $table->merge();
        
        $this->assertEquals(100,$table->getOption('generatorSeed'));
        $this->assertEquals('srand',$table->getOption('randomGenerator'));
        
    }
    
}