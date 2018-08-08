<?php
namespace Faker\Tests\Engine\Common\Formatter;

use Faker\Tests\Base\AbstractProject;
use Faker\Tests\Engine\Common\Formatter\Mock\MockFormatter;
    
    
class BaseFormatterTest extends AbstractProject
{

    public function testOptionInterface()
    {
        $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $writer   = $this->createMock('Faker\Components\Writer\WriterInterface');
        $visitor  = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base = new MockFormatter($event,$writer,$platform,$visitor);
        
        
        $base->setOption('option1',1);
        $base->setOption('option2','anoption');
        
        $this->assertEquals(1,$base->getOption('option1'));
        $this->assertEquals('anoption',$base->getOption('option2'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Option at option100 does not exist
      */
    public function testMissingOptionException()
    {
         $event    = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $writer   = $this->createMock('Faker\Components\Writer\WriterInterface');
        $visitor  = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base = new MockFormatter($event,$writer,$platform,$visitor);
        $base->getOption('option100');
        
    }
    
    
    public function testProperties()
    {
        $writer = $this->createMock('\Faker\Components\Writer\WriterInterface');
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $event = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base = new MockFormatter($event,$writer,$platform,$visitor);
        
        $this->assertEquals($event,$base->getEventDispatcher());
        $this->assertEquals($writer,$base->getWriter());    
        $this->assertEquals($platform,$base->getPlatform());
        $this->assertEquals($visitor,$base->getVisitor());
        
        
        $writerB = $this->createMock('\Faker\Components\Writer\WriterInterface');
        $visitorB = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $eventB = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $platformB = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base->setWriter($writerB);
        $base->setVisitor($visitorB);
        $base->setEventDispatcher($eventB);
        $base->setPlatform($platformB);

        $this->assertEquals($writerB,$base->getWriter());
        $this->assertEquals($visitorB,$base->getVisitor());
        $this->assertEquals($eventB,$base->getEventDispatcher());
        $this->assertEquals($platformB,$base->getPlatform());
        
    }
    
    
    public function testMergeMissingDefaults()
    {
        $writer = $this->createMock('\Faker\Components\Writer\WriterInterface');
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $event = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base = new MockFormatter($event,$writer,$platform,$visitor);
        
        $base->validate();
        $this->assertEquals(false,$base->getOption('splitOnTable'));
        $this->assertEquals('{prefix}_{body}_{suffix}_{seq}.{ext}',$base->getOption('outFileFormat'));
            
    }
    
    public function testMergeMissingValues()
    {
        $writer = $this->createMock('\Faker\Components\Writer\WriterInterface');
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $event = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base = new MockFormatter($event,$writer,$platform,$visitor);
        
        $base->setOption('splitOnTable',null);
        $base->validate();
        $this->assertFalse($base->getOption('splitOnTable'));
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage  Invalid type for path "config.splitOnTable". Expected boolean, but got string
      */
    public function testMergeBadValues()
    {
        $writer = $this->createMock('\Faker\Components\Writer\WriterInterface');
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $event = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base = new MockFormatter($event,$writer,$platform,$visitor);
        
        $base->setOption('splitOnTable','true');
        $base->validate();
        
    }
    
    
    public function testMergeGoodValues()
    {
        $writer = $this->createMock('\Faker\Components\Writer\WriterInterface');
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $event = $this->createMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $platform = $this->getMockBuilder('\Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->getMockForAbstractClass();
        
        $base = new MockFormatter($event,$writer,$platform,$visitor);
        
        $base->setOption('splitOnTable',true);
        $base->setOption('outFileFormat','{suffix}_{seq}.{ext}');
        $base->validate();
        
        $this->assertTrue($base->getOption('splitOnTable'));
        $this->assertEquals('{suffix}_{seq}.{ext}',$base->getOption('outFileFormat'));
    }
    
}
/* End of File */