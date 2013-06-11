<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\ColumnNode;
use Doctrine\DBAL\Types\Type;
use Faker\Tests\Base\AbstractProject;
    
class ColumnTest extends AbstractProject
{
    
    public function testImplementsInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $column = new ColumnNode($id,$event);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$column);
        $this->assertInstanceOf('Faker\Components\Engine\Common\OptionInterface',$column);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\VisitorInterface',$column);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\SerializationInterface',$column);
    
    }
    
     
    public function testOptionsProperties()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('locale','en');
        $this->assertEquals($column->getOption('locale'),'en');
        
    }
    
    
    public function testToXml()
    {
        $id = 'column_1';
        $generate = 100;
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $type  = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        
        $type->expects($this->once())
             ->method('getName')
             ->will($this->returnValue('integer'));
        
        $column = new ColumnNode($id,$event);
        $column->setDBALType($type);
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\XML\Mock\MockNode')->disableOriginalConstructor()->getMock();
        $child_a->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<type></type>'));
                
        $column->addChild($child_a);        
        
        
        $xml = $column->toXml();
        $this->assertContains('<column name="column_1" type="integer">',$xml );
        $this->assertContains('<type></type>',$xml );
        $this->assertContains('</column>',$xml);
    
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage The child node "name" at path "config" must be configured
      */
    public function testValidateFailedNoNameSet()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('type','integer');
        $column->validate();
     
    }
    
     /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage Column:: Column DBAL Type must be included
      */
    public function testValidateFailedDBALTypeNotString()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('type',1);
        $column->setOption('name','column');
        $column->validate();
     
    }
    
    
    public function testDefaultLocaleSet()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('name','schemaNode');
        $column->setOption('type','integer');
        
        $column->validate();
        $this->assertEquals('en',$column->getOption('locale'));
    }
    
    
    public function testNoDefaultsEmptyForGenerator()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('name','schemaNode');
        $column->setOption('type','integer');
        
        $column->validate();
        
        $this->assertFalse($column->hasOption('randomGenerator'));
        $this->assertFalse($column->hasOption('generatorSeed'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testNotIntergerRefusedForGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('name','schemaNode');
        $column->setOption('generatorSeed','a non integer');
        $column->setOption('type','integer');
        $column->validate();
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testNullNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('name','schemaNode');
        $column->setOption('generatorSeed',null);
        $column->setOption('type','integer');
        
        $column->validate();
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testEmptyStringNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $column->setOption('name','schemaNode');
        $column->setOption('generatorSeed','');
        $column->setOption('type','integer');
        $column->validate();
    }
    
    
    public function testValidationCallsChildren()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column = new ColumnNode($id,$event);
        
        $childA = $this->getMockBuilder('Faker\Tests\Engine\XML\Mock\MockNode')->disableOriginalConstructor()->getMock();
        $childA->expects($this->once())
                ->method('validate');
        
        $column->addChild($childA);
        
        $column->setOption('name','schemaNode');
        $column->setOption('generatorSeed',100);
        $column->setOption('randomGenerator','srand');
        $column->setOption('type','integer');
        
        
        $column->validate();
        
        $this->assertEquals(100,$column->getOption('generatorSeed'));
        $this->assertEquals('srand',$column->getOption('randomGenerator'));
        
    }
    
    
    public function testTypeInterfaceProperties()
    {
        $id         = 'fk_table_1';
        $event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $node       = new ColumnNode($id,$event); 
        
        $utilities  = $this->getMock('Faker\Components\Engine\Common\Utilities');
        $generator  = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $locale     = $this->getMock('Faker\Locale\LocaleInterface');
        
        $node->setUtilities($utilities);
        $node->setLocale($locale);
        $node->setGenerator($generator);
        
        $this->assertEquals($utilities,$node->getUtilities());
        $this->assertEquals($locale,$node->getLocale());
        $this->assertEquals($generator,$node->getGenerator());
        
    }
    
}