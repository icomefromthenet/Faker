<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\FormatterNode;
use Faker\Components\Engine\Common\Formatter\FormatterInterface;
use Doctrine\DBAL\Types\Type;
use Faker\Tests\Base\AbstractProject;
    
class FormatterTest extends AbstractProject
{
    
    public function testImplementsInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        
        $formatter = $this->getMock('Faker\Components\Engine\Common\Formatter\FormatterInterface');
        
        $formatterNode = new FormatterNode($id,$event,$formatter);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$formatterNode);
        $this->assertInstanceOf('Faker\Components\Engine\Common\OptionInterface',$formatterNode);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\VisitorInterface',$formatterNode);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\SerializationInterface',$formatterNode);
    }
    
     
    public function testOptionsProperties()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $formatter = $this->getMock('Faker\Components\Engine\Common\Formatter\FormatterInterface');
        $formatter->expects($this->once())->method('setOption')->with($this->equalTo('locale'),$this->equalTo('en'));
        
        $formatterNode = new FormatterNode($id,$event,$formatter);
        $formatterNode->setOption('locale','en');
        
    }
    
    public function testTypeInterfaceProperties()
    {
        $id         = 'fk_table_1';
        $event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
         $formatter = $this->getMock('Faker\Components\Engine\Common\Formatter\FormatterInterface');
        $node       = new FormatterNode($id,$event,$formatter); 
        
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