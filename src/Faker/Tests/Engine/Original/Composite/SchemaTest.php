<?php
namespace Faker\Tests\Engine\Original\Composite;

use Faker\Components\Engine\Original\Composite\Schema,
    Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Formatter\FormatEvents,
    Faker\Components\Engine\Original\Formatter\GenerateEvent,
    Faker\Tests\Base\AbstractProject;

class SchemaTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
     
        $schema = new Schema($id,null,$event);
        
        $this->assertInstanceOf('Faker\Components\Engine\Original\Composite\CompositeInterface',$schema);
        $this->assertInstanceOf('Faker\Components\Engine\Original\Composite\BaseComposite',$schema);
    }
    
    
   
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();

        $event->expects($this->exactly(2))
              ->method('dispatch')
              ->with($this->logicalOr($this->stringContains(FormatEvents::onSchemaStart), $this->stringContains(FormatEvents::onSchemaEnd)),$this->isInstanceOf('\Faker\Components\Engine\Original\Formatter\GenerateEvent'));
              
        $schema = new Schema($id,null,$event,array('name' => $id));
        $schema->generate(1,array());
        
    }
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('name' => $id));
     
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'));
       
        $child_b = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'));
       
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        
        $schema->generate(1,array());
   
    }
    
     public function testOptionsProperties()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
     
        $schema = new Schema($id,null,$event,array('name' => $id));
        
        $schema->setOption('locale','en');
        $this->assertEquals($schema->getOption('locale'),'en');
        
    }
    
    public function testToXml()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('name' => $id));
     
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<table></table>'));
                
        $child_b = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<table></table>'));
          
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        
        $xml = $schema->toXml();
        
        $this->assertContains('<schema name="schema_1">', $xml);
        $this->assertContains('</schema>', $xml);
        $this->assertContains('<?xml version="1.0"?>', $xml);
        
    }
    
    
    public function testProperties()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('name' => $id));
     
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        $schema->setWriters(array('one','two'));
        
        $this->assertEquals($schema->getChildren(),array($child_a,$child_b));
        $this->assertSame($schema->getEventDispatcher(),$event);
        $this->assertEquals($id,$schema->getId());
        $this->assertEquals(array('one','two'),$schema->getWriters());
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Schema must have at least 1 table
      */
    public function testValidateWithException()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('name' => $id));
     
        $schema->merge();
        $schema->validate();
     
    }
    
    public function testValidate()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('name' => $id));
        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->merge();
        $schema->validate();
        
        # test the default option was parsed
        $this->assertEquals('en',$schema->getOption('locale'));
     
    }
    
     
    public function testDefaultLocaleSetNullPass()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('locale'=>null,'name' => $id));

        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->merge();
        
         # test the default option was parsed
        $this->assertEquals('en',$schema->getOption('locale'));
        
    }
    
    public function testNoDefaultsSetForGenerator()
    {
        
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('locale'=>null,'name' => $id));

        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->merge();
        $this->assertFalse($schema->hasOption('randomGenerator'));
        $this->assertFalse($schema->hasOption('generatorSeed'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": generatorSeed must be an integer
      */
    public function testNotIntergerRefusedForGeneratorSeed()
    {
        
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('locale'=>null,'name' => $id));

        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->setOption('generatorSeed','a non integer');
        
        $schema->merge();
        
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": generatorSeed must be an integer
      */
    public function testNullNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('locale'=>null,'name' => $id));

        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->setOption('generatorSeed',null);
        
        $schema->merge();
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": generatorSeed must be an integer
      */
    public function testEmptyStringNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('locale'=>null,'name' => $id));

        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->setOption('generatorSeed','');
        
        $schema->merge();
        
        
    }
    
    
    public function testGeneratorOptionsAccepted()
    {
        
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new Schema($id,null,$event,array('locale'=>null,'name' => $id));

        $writer = $this->getMockBuilder('Symfony\Components\Faker\Formatter\FormatterInterface')->getMock();
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
          
        $schema->addChild($child_a);        
        $schema->setWriters(array($writer));
        
        $schema->setOption('generatorSeed',100);
        $schema->setOption('randomGenerator','srand');
        
        $schema->merge();
        
        $this->assertEquals(100,$schema->getOption('generatorSeed'));
        $this->assertEquals('srand',$schema->getOption('randomGenerator'));
        
    }
    
}