<?php
namespace Faker\Test\Config;

use Faker\Io\Io,
    Faker\Components\Config\Entity,
    Faker\Components\Config\Writer,
    Faker\Tests\Base\AbstractProject;

class WriterTest extends AbstractProject
{
    
    public function testProperties()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();
        $writer = new Writer($io);
        
        $this->assertSame($io,$writer->getIo());
    }
    
    
    
    public function testGoodConfig()
    {
        
        $param = $this->getMockConfigEntityParm();
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(false));
        
        $writer = new Writer($io);
        $writer->write($param,$alias);

    }

    
    public function testGoodConfigOverriteFlag()
    {
        
        $param = $this->getMockConfigEntityParm();
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(true));
        
        $writer = new Writer($io);
        $writer->write($param,$alias,true);

    }
    
}

/* End of File */