<?php

namespace Faker\Test\Config;

use Faker\Io\Io,
    Faker\Components\Config\Entity,
    Faker\Components\Config\Loader,
    Faker\Tests\Base\AbstractProject;

class LoaderTest extends AbstractProject
{
    
    
    public function testProperties()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
    
        $loader = new Loader($io);
        
        $this->assertSame($io,$loader->getIo());
        
        # test that default file is set
        $this->assertEquals(Loader::DEFAULTNAME,'default');
        
        # test that default file ext is set
        $this->assertEquals(Loader::EXTENSION,'.php');

    }



    public function testExistsNoFileExt()
    {
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
    
        $io->expects($this->once())
           ->method('exists')
           ->with($this->equalTo('default.php'))
           ->will($this->returnValue(false));
    
        $loader = new Loader($io);
        
        $this->assertFalse($loader->exists('default'));        
    }
    
    public function testExists()
    {
        
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
    
        $io->expects($this->once())
           ->method('exists')
           ->with($this->equalTo('default.php'))
           ->will($this->returnValue(false));
    
        $loader = new Loader($io);
        
        $this->assertFalse($loader->exists('default.php'));        
    }
    
    
    public function testLoadFails()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        
        $io->expects($this->once())
           ->method('load') 
           ->will($this->returnValue(null));
        
        $loader = new Loader($io);
        $this->assertEquals(null,$loader->load('myconfig.php',new Entity()));
    }
    
    /**
      *  @depends  testLoadFails
      */
    public function testLoadDefaultName()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo(Loader::DEFAULTNAME . Loader::EXTENSION),$this->equalTo(null))
           ->will($this->returnValue(null));
        
        $loader = new Loader($io);
        $this->assertEquals(null,$loader->load('',new Entity()));
        
    }
    
    
    /**
      * @depends testLoadFails 
      */
    public function testLoad()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('Faker\Components\Config\Entity')->getMock();
        
        $ent->expects($this->once())
            ->method('merge')
            ->with($this->equalTo($this->getMockConfigEntityParm()));
        
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo('myconfig.php'),$this->equalTo(null))
           ->will($this->returnValue($this->getMockConfigEntityParm()));
        
        $loader = new Loader($io);
        $this->assertSame($ent,$loader->load('myconfig.php',$ent));
    }
    
}
/* End of File */