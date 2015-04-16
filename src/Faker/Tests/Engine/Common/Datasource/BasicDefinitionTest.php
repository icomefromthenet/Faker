<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\AbstractDefinition;

class BasicDefinitionTest extends AbstractProject
{
    
    public function testImplementsCorrectInterfaces()
    {
        $def = new AbstractDefinition();
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\TypeDefinitionInterface',$def);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\NodeInterface',$def);
    }
    
    
    public function testNoErrorDuringPropertyAssignment()
    {
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $gen    = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();     
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock(); 
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
     
        $parent = $this->getMock('Faker\Components\Engine\Common\Builder\NodeInterface');
     
     
        $def = new AbstractDefinition();
        
        $def->locale($locale);
        $def->generator($gen);
        $def->utilities($utilities);
        $def->eventDispatcher($event);
        $def->database($database);
        $def->templateLoader($template);
        $def->setParent($parent);
        
        $this->assertEquals($parent,$def->getParent());
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage must be implemented by actual definition
     */ 
    public function testGetNodeThrowsError()
    {
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $gen    = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();     
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock(); 
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
     
        $parent = $this->getMock('Faker\Components\Engine\Common\Builder\NodeInterface');
     
     
        $def = new AbstractDefinition();
        
        $def->locale($locale);
        $def->generator($gen);
        $def->utilities($utilities);
        $def->eventDispatcher($event);
        $def->database($database);
        $def->templateLoader($template);
        $def->setParent($parent);
        
        #method must be implemented in child
        $def->getNode();
    }
    
}
/* End of File */