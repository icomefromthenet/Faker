<?php
namespace Faker\Tests\Engine\Common\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Writer\WriterInterface;
use Faker\Components\Engine\Common\Formatter\FormatterFactory;


class FactoryTest extends AbstractProject
{
    
    public function testFactoryConstructor()
    {
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $writer_manager   = $this->getMockBuilder('\Faker\Components\Writer\Manager')
                         ->disableOriginalConstructor()
                         ->getMock();
        $visitor  = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        
        $factory = new FormatterFactory($event,$writer_manager,$visitor);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Formatter\FormatterFactory',$factory);
        
    }
    
    
    public function testCreateNoOptons()
    {
       
        $formatter_full = '\\Faker\\Components\\Engine\\Common\\Formatter\\Sql';
        $formatter_key = 'Sql';
        
        $event    = $this->getMockBuilder('\\Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $event->expects($this->once())
              ->method('addSubscriber');
  
        $writer_instance = $this->getMockBuilder('\Faker\Components\Writer\WriterInterface')->getMock();
        
        
        $writer_manager   = $this->getMockBuilder('\Faker\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $writer_manager->expects($this->once())
               ->method('getWriter')
               ->with($this->equalTo('mysql'),$this->equalTo('sql'))
               ->will($this->returnValue($writer_instance));
                   
                        
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $platform->expects($this->once())
                  ->method('getName')
                  ->will($this->returnValue('mysql'));
        
        $visitor  = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        
        $factory = new FormatterFactory($event,$writer_manager,$visitor);
        
        $this->assertInstanceOf($formatter_full,$factory->create($formatter_key,$platform,array()));
        
    }
    
     
    public function testPhpunitFormatterCreateWithOptions()
    {
        $formatter_full = '\\Faker\\Components\\Engine\\Common\\Formatter\\Phpunit';
        $formatter_key = 'Phpunit';
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $event->expects($this->once())
              ->method('addSubscriber');
        
        $writer_instance = $this->getMockBuilder('\Faker\Components\Writer\WriterInterface')->getMock();
        
        
        $writer_manager  = $this->getMockBuilder('\Faker\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $writer_manager->expects($this->once())
               ->method('getWriter')
               ->with($this->equalTo('mysql'),$this->equalTo('phpunit'))
               ->will($this->returnValue($writer_instance));
       
    
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $platform->expects($this->once())
                  ->method('getName')
                  ->will($this->returnValue('mysql'));
        
        $visitor  = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        
        $factory = new FormatterFactory($event,$writer_manager,$visitor);
        
        $this->assertInstanceOf($formatter_full,$instance = $factory->create($formatter_key,$platform,array('opt1' => '1', 'opt2' => 2)));
        
        # assert Options passed
        $this->assertEquals('1',$instance->getOption('opt1'));
        $this->assertEquals( 2,$instance->getOption('opt2'));
        
    }
    
    
    public function testCreateLowercaseKeyWithOptions()
    {
        $formatter_full = '\\Faker\\Components\\Engine\\Common\\Formatter\\Phpunit';
        $formatter_key = 'phpunit';
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $event->expects($this->once())
              ->method('addSubscriber');
        
        
        $writer_instance = $this->getMockBuilder('\Faker\Components\Writer\WriterInterface')->getMock();
        
        
        $writer_manager   = $this->getMockBuilder('\Faker\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $writer_manager->expects($this->once())
               ->method('getWriter')
               ->with($this->equalTo('mysql'),$this->equalTo('phpunit'))
               ->will($this->returnValue($writer_instance));
       
        
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $platform->expects($this->once())
                  ->method('getName')
                  ->will($this->returnValue('mysql'));
        
        $visitor  = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        
        $factory = new FormatterFactory($event,$writer_manager,$visitor);
        
        $this->assertInstanceOf($formatter_full,$instance = $factory->create($formatter_key,$platform,array('opt1' => '1', 'opt2' => 2)));
        
        # assert Options passed
        $this->assertEquals('1',$instance->getOption('opt1'));
        $this->assertEquals( 2,$instance->getOption('opt2'));
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException 
      */
    public function testCreateBadKey()
    {
        $formatter_key = 'bad_key';
        
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $writer_manager   = $this->getMockBuilder('\Faker\Components\Writer\Manager')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $visitor  = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        
        $factory = new FormatterFactory($event,$writer_manager,$visitor);
        
        $factory->create($formatter_key,$platform,array());
        
    }
    
   
    
}
/* End of File */