<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\FormatterNode;
use Faker\Components\Engine\Common\Formatter\FormatterInterface;
use Doctrine\DBAL\Types\Type;
use Faker\Tests\Base\AbstractProject;
    
class ColumnTest extends AbstractProject
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
}