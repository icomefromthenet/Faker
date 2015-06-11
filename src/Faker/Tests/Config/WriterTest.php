<?php
namespace Faker\Tests\Config;

use Faker\Io\Io;
use Faker\Components\Config\Entity;
use Faker\Components\Config\Writer;
use Faker\Tests\Base\AbstractProject;

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
        $entity->setConnectionName('connect1');
        
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(''),$this->isType('string'),$this->equalTo(false));
        
        $writer = new Writer($io);
        $writer->write(array($entity),$alias,false);

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
        $entity->setConnectionName('connect1');
       
        $alias = 'default';
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();

        $io->expects($this->once())
            ->method('write')
            ->with($this->equalTo($alias.'.php'),$this->equalTo(null),$this->isType('string'),$this->equalTo(true));
        
        $writer = new Writer($io);
        $writer->write(array($entity),$alias,true);

    }
    
}

/* End of File */