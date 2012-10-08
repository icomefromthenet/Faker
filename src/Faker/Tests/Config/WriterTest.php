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
        $entity = new Entity();
        $entity->setCharset('latin1');
        $entity->setHost('localhost');
        $entity->setMemory(':memory');
        $entity->setPassword('vagrant');
        $entity->setPath('path/to/db/db.sqlite');
        $entity->setPort('3306');
        $entity->setSchema('sakila');
        $entity->setType('pdo_mysql');
        $entity->setUnixSocket('path/to/socker/socket.sock');
        $entity->setUser('root');
        
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(false));
        
        $writer = new Writer($io);
        $writer->write($entity,$alias,false);

    }

    
    public function testGoodConfigOverriteFlag()
    {
        
        $entity = new Entity();
        $entity->setCharset('latin1');
        $entity->setHost('localhost');
        $entity->setMemory(':memory');
        $entity->setPassword('vagrant');
        $entity->setPath('path/to/db/db.sqlite');
        $entity->setPort('3306');
        $entity->setSchema('sakila');
        $entity->setType('pdo_mysql');
        $entity->setUnixSocket('path/to/socker/socket.sock');
        $entity->setUser('root');
       
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(true));
        
        $writer = new Writer($io);
        $writer->write($entity,$alias,true);

    }
    
}

/* End of File */