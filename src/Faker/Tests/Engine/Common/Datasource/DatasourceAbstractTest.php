<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Tests\Engine\Common\Datasource\Mock\MockDatasource;
use Faker\Components\Engine\EngineException;

class DatasourceAbstractTest extends AbstractProject
{
    
    public function testBaiscProperties()
    {
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $gen    = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();     
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        
        $mock = new MockDatasource();
       
        $mock->setUtilities($utilities);
        $mock->setGenerator($gen);
        $mock->setDatabase($database);
        $mock->setEventDispatcher($event);
        $mock->setLocale($locale);
       
        $this->assertEquals($utilities,$mock->getUtilities());
        $this->assertEquals($gen,$mock->getGenerator());
        $this->assertEquals($database,$mock->getDatabase());
        $this->assertEquals($event,$mock->getEventDispatcher());
        $this->assertEquals($locale,$mock->getLocale());
        
        
    }
    
    public function testOptionsInterface()
    {
        $mock = new MockDatasource();
        
        $optionName = 'myOption';
        $optionValue = 6;
        
        $mock->setOption($optionName,$optionValue);
        
        $this->assertEquals($mock->hasOption($optionName),true);
        $this->assertEquals($mock->hasOption('noop'),false);
        $this->assertEquals($optionValue,$mock->getOption($optionName));
        
        $this->assertInstanceOf('Symfony\Component\Config\Definition\Builder\TreeBuilder',$mock->getConfigTreeBuilder());
        
        
    }
    
    public function testConfigTreebuilderCallsExtension()
    {
        $mock = $this->getMockBuilder('Faker\Components\Engine\Common\Datasource\AbstractDatasource')
                     ->setMethods(array('initSource','fetchOne','flushSource','cleanupSource','getConfigTreeExtension'))
                     ->getMock();
        
        $mock->expects($this->once())
             ->method('getConfigTreeExtension');
             
        $mock->getConfigTreeBuilder();
    }
    
    public function testValidateWhenNoErrors()
    {
         $mock = new MockDatasource();
         
         $this->assertTrue($mock->validate());
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage BadOption is equal to bad
     */ 
    public function testValidateWhenErrors()
    {
        $mock = new MockDatasource();
        $mock->setOption('badOption','bad');
        $mock->validate();
    }
    
    
}
/* End of File */