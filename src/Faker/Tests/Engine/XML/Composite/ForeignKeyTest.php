<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\ForeignKeyNode;
use Faker\Tests\Base\AbstractProject;
    
class ForeignKeyTest extends AbstractProject
{
    
    public function testImplementsInterface()
    {
        $id = 'fk_table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $node = new ForeignKeyNode($id,$event);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$node);
        $this->assertInstanceOf('Faker\Components\Engine\Common\OptionInterface',$node);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\VisitorInterface',$node);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\SerializationInterface',$node);
    }
    
    
    public function testTypeInterfaceProperties()
    {
        $id         = 'fk_table_1';
        $event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $node       = new ForeignKeyNode($id,$event); 
        
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