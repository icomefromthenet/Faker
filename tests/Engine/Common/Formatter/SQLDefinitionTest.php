<?php
namespace Faker\Tests\Engine\Common\Formatter;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Formatter\SqlFormatterDefinition;
use Faker\Components\Engine\Common\Formatter\Sql;
    
    
class SQLDefinitionTest extends AbstractProject
{

    public function testExtendsBaseClass()
    {
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $formatFactory = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Formatter\\FormatterFactory')->disableOriginalConstructor()->getMock();
        $platfomFactory = $this->getMockBuilder('Faker\PlatformFactory')->getMock();
        
        $def = new SqlFormatterDefinition($event,$formatFactory,$platfomFactory);
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Formatter\\AbstractDefinition',$def);
        
    }
    
    public function testCustomOptions()
    {
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $formatFactory = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Formatter\\FormatterFactory')->disableOriginalConstructor()->getMock();
        $platfomFactory = $this->getMockBuilder('Faker\PlatformFactory')->getMock();
        
        $def = new SqlFormatterDefinition($event,$formatFactory,$platfomFactory);
        
        $this->assertEquals($def,$def->maxLines(100));
        $this->assertEquals($def,$def->singleFileMode());
        $this->assertEquals($def,$def->writeToDatabase('mydba'));
        
    }
    
    public function testNodeConstruction()
    {
        $mockPlatform = new MySqlPlatform();
        $mockFormatter = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Formatter\\FormatterInterface')->getMock();
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $formatFactory = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Formatter\\FormatterFactory')->disableOriginalConstructor()->getMock();
        
        $platfomFactory = $this->getMockBuilder('Faker\PlatformFactory')->getMock();
        
        $platfomFactory->expects($this->once())
                       ->method('create')
                       ->with($this->equalTo('mysql'))
                       ->will($this->returnValue($mockPlatform));
        
        $formatFactory->expects($this->once())
                      ->method('create')
                      ->with($this->equalTo('sql'),$this->equalTo($mockPlatform),$this->equalTo(array(
                          Sql::CONFIG_WRITE_TO_DATABASE => 'name',
                          Sql::CONFIG_OPTION_SINGLE_FILE_MODE => true,
                          Sql::CONFIG_OPTION_MAX_LINES => 105
                        )))
                      ->will($this->returnValue($mockFormatter));
        
        
        $def = new SqlFormatterDefinition($event,$formatFactory,$platfomFactory);
        
        $def->writeToDatabase('name');
        $def->singleFileMode();
        $def->maxLines(105);
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\FormatterNode',$def->getNode());
    }
   
    
}
/* End of File */