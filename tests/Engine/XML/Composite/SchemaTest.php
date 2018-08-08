<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\SchemaNode;
use Faker\Tests\Base\AbstractProject;

class SchemaTest extends AbstractProject
{
    
    public function testImplementsInterfaces()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
     
        $schema = new SchemaNode($id,$event);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$schema);
        $this->assertInstanceOf('Faker\Components\Engine\Common\OptionInterface',$schema);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\VisitorInterface',$schema);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\SerializationInterface',$schema);
        
    }
    
    
    public function testOptionsProperties()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $schema->setOption('locale','en');
        $this->assertEquals($schema->getOption('locale'),'en');
        
    }
    
    public function testToXml()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\XML\Mock\MockNode')->disableOriginalConstructor()->getMock();
        $child_a->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<table></table>'));
                
        $child_b = $this->getMockBuilder('Faker\Tests\Engine\XML\Mock\MockNode')->disableOriginalConstructor()->getMock();
        $child_b->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<table></table>'));
          
        $schema->addChild($child_a);        
        $schema->addChild($child_b);        
        
        $xml = $schema->toXml();
        
        $this->assertContains('<schema name="schema_1">', $xml);
        $this->assertContains('<table></table>', $xml);
        $this->assertContains('</schema>', $xml);
        $this->assertContains('<?xml version="1.0"?>', $xml);
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage The child node "name" at path "config" must be configured
      */
    public function testValidateFailedNoNameSet()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $schema->validate();
     
    }
    
    
    public function testDefaultLocaleSet()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $schema->setOption('name','schemaNode');
        
        $schema->validate();
        $this->assertEquals('en',$schema->getOption('locale'));
    }
    
    
    public function testNoDefaultsEmptyForGenerator()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $schema->setOption('name','schemaNode');
        
        $schema->validate();
        
        $this->assertFalse($schema->hasOption('randomGenerator'));
        $this->assertFalse($schema->hasOption('generatorSeed'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testNotIntergerRefusedForGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $schema->setOption('name','schemaNode');
        $schema->setOption('generatorSeed','a non integer');        
        $schema->validate();
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testNullNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $schema->setOption('name','schemaNode');
        $schema->setOption('generatorSeed',null);        
        $schema->validate();
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testEmptyStringNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $schema->setOption('name','schemaNode');
        $schema->setOption('generatorSeed','');        
        $schema->validate();
    }
    
    
    public function testValidationCallsChildren()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema = new SchemaNode($id,$event);
        
        $childA = $this->getMockBuilder('Faker\Tests\Engine\XML\Mock\MockNode')->disableOriginalConstructor()->getMock();
        $childA->expects($this->once())
                ->method('validate');
        
        $schema->addChild($childA);
        
        $schema->setOption('name','schemaNode');
        $schema->setOption('generatorSeed',100);
        $schema->setOption('randomGenerator','srand');
        
        
        $schema->validate();
        
        $this->assertEquals(100,$schema->getOption('generatorSeed'));
        $this->assertEquals('srand',$schema->getOption('randomGenerator'));
        
    }
    
    public function testTypeInterfaceProperties()
    {
        $id         = 'fk_table_1';
        $event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $node       = new SchemaNode($id,$event); 
        
        $utilities  = $this->createMock('Faker\Components\Engine\Common\Utilities');
        $generator  = $this->createMock('PHPStats\Generator\GeneratorInterface');
        $locale     = $this->createMock('Faker\Locale\LocaleInterface');
        
        $node->setUtilities($utilities);
        $node->setLocale($locale);
        $node->setGenerator($generator);
        
        $this->assertEquals($utilities,$node->getUtilities());
        $this->assertEquals($locale,$node->getLocale());
        $this->assertEquals($generator,$node->getGenerator());
        
    }
    
}