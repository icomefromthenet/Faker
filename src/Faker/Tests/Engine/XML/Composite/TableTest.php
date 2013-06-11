<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\TableNode;
use Faker\Tests\Base\AbstractProject;

class TableTest extends AbstractProject
{
    
    public function testImplementsInterfaces()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $generate = 100;
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();

        
        $table = new TableNode($id,$event);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$table);
        $this->assertInstanceOf('Faker\Components\Engine\Common\OptionInterface',$table);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\VisitorInterface',$table);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\SerializationInterface',$table);
    }
    
    
    public function testToXml()
    {
        $id = 'table_1';
        $generate = 100;
       
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        
        $table = new TableNode($id,$event);
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\XML\Mock\MockNode')->disableOriginalConstructor()->getMock();
        $child_a->expects($this->once())
                ->method('toXml')
                ->will($this->returnValue('<column></column>'));
                
        $table->addChild($child_a);        
        
        $xml = $table->toXml();
        $this->assertContains('<table name="table_1" generate="0">',$xml);
        $this->assertContains('<column></column>', $xml);
        $this->assertContains('</table>',$xml);    
    }
    
 
 
     
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage The child node "generate" at path "config" must be configured
      */
    public function testValidateFailedGenerateNotSet()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->setOption('name','tableNode');
        
        
        $table->validate();
     
    }
    
     /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage Table::Generate must be and integer
      */
    public function testValidateFailedGenerateFailsNonInteger()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->setOption('name','tableNode');
        $table->setOption('generate','tableNode');
        
        $table->validate();
     
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage The child node "name" at path "config" must be configured
      */
    public function testValidateFailedNoNameSet()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->validate();
     
    }
    
     public function testDefaultLocaleSet()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->setOption('name','tableNode');
        $table->setOption('generate',100);
        $table->validate();
        $this->assertEquals('en',$table->getOption('locale'));
    }
    
    
    public function testNoDefaultsEmptyForGenerator()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->setOption('name','tableNode');
        $table->setOption('generate',100);
        
        $table->validate();
        
        $this->assertFalse($table->hasOption('randomGenerator'));
        $this->assertFalse($table->hasOption('generatorSeed'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testNotIntergerRefusedForGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->setOption('name','tableNode');
        $table->setOption('generatorSeed','a non integer');
        $table->setOption('generate',100);
        $table->validate();
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testNullNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->setOption('name','tableNode');
        $table->setOption('generatorSeed',null);
        $table->setOption('generate',100);
        $table->validate();
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage generatorSeed must be an integer
      */
    public function testEmptyStringNotAcceptedGeneratorSeed()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $table = new TableNode($id,$event);
        
        $table->setOption('name','tableNode');
        $table->setOption('generatorSeed','');
        $table->setOption('generate',100);
        $table->validate();
    }
    
    public function testTypeInterfaceProperties()
    {
        $id         = 'fk_table_1';
        $event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $node       = new TableNode($id,$event); 
        
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