<?php
namespace Faker\Tests\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Faker\Components\Writer\WriterInterface,
    Doctrine\DBAL\Platforms\AbstractPlatform,
    Faker\Components\Faker\Formatter\FormatterFactory,
    Faker\Tests\Base\AbstractProject;

class FactoryTest extends AbstractProject
{
    
    public function testFactoryConstructor()
    {
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $writer_manager   = $this->getMockBuilder('\Faker\Components\Writer\Manager')
                         ->disableOriginalConstructor()
                         ->getMock();
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf('Faker\Components\Faker\Formatter\FormatterFactory',$factory);
        
    }
    
    
    public function testCreateNoOptons()
    {
       
        $formatter_full = '\\Faker\\Components\\Faker\\Formatter\\Sql';
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
        
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf($formatter_full,$factory->create($formatter_key,$platform,array()));
        
    }
    
     
    public function testPhpunitFormatterCreateWithOptions()
    {
        $formatter_full = '\\Faker\\Components\\Faker\\Formatter\\Phpunit';
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
        
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf($formatter_full,$instance = $factory->create($formatter_key,$platform,array('opt1' => '1', 'opt2' => 2)));
        
        # assert Options passed
        $this->assertEquals('1',$instance->getOption('opt1'));
        $this->assertEquals( 2,$instance->getOption('opt2'));
        
    }
    
    
    public function testCreateLowercaseKeyWithOptions()
    {
        $formatter_full = '\\Faker\\Components\\Faker\\Formatter\\phpunit';
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
        
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $this->assertInstanceOf($formatter_full,$instance = $factory->create($formatter_key,$platform,array('opt1' => '1', 'opt2' => 2)));
        
        # assert Options passed
        $this->assertEquals('1',$instance->getOption('opt1'));
        $this->assertEquals( 2,$instance->getOption('opt2'));
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception 
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
        
        $factory = new FormatterFactory($event,$writer_manager);
        
        $factory->create($formatter_key,$platform,array());
        
    }
    
   
    
}
/* End of File */