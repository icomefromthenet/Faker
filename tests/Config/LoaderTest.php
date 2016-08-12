<?php
namespace Faker\Tests\Config;

use Faker\Io\Io,
    Faker\Components\Config\EntityInterface,
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
        $ent = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
        $io->expects($this->once())
           ->method('load') 
           ->will($this->returnValue(null));
        
        $loader = new Loader($io);
        $this->assertEquals(null,$loader->load('myconfig.php',$ent));
    }
    
    /**
      *  @depends  testLoadFails
      */
    public function testLoadDefaultName()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
         
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo(Loader::DEFAULTNAME . Loader::EXTENSION),$this->equalTo(null))
           ->will($this->returnValue(null));
        
        $loader = new Loader($io);
        $this->assertEquals(null,$loader->load('',$ent));
        
    }
    
    
    protected function matchFakerDBEntity($config,EntityInterface $ent)
    {
        $this->assertEquals($config['type'],$ent->getType());
        $this->assertEquals($config['schema'],$ent->getSchema());
        $this->assertEquals($config['user'],$ent->getUser());
        $this->assertEquals($config['password'],$ent->getPassword());
        $this->assertEquals($config['host'],$ent->getHost());
        $this->assertEquals($config['port'],$ent->getPort());
        $this->assertEquals($config['socket'],$ent->getUnixSocket());
        $this->assertEquals($config['path'],$ent->getPath());
        $this->assertEquals($config['memory'],$ent->getMemory());
        $this->assertEquals($config['charset'],$ent->getCharset());
        $this->assertEquals($config['connName'],$ent->getConnectionName());
        
    }
    
    /**
      * @depends testLoadFails 
      */
    public function testLoadDepreciate()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
        
        
           
        $data = array(
            'type'            => 'pdo_mysql',
            'schema'          => 'sakila',
            'user'            => 'root',
            'password'        => 'vagrant',
            'host'            => 'localhost',
            'port'            => '3306',
            'socket'          => false,
            'path'            => false,
            'memory'          => false,
            'charset'         => false,
            'connName'        => 'myconnectName'
        );
        
       
        
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo('myconfig.php'),$this->equalTo(null))
           ->will($this->returnValue($data));
        
        $loader = new Loader($io);
        $returnStack = $loader->load('myconfig.php');
        
        $this->assertInternalType('array',$returnStack);
        $this->assertCount(1,$returnStack);
        $this->matchFakerDBEntity($data,$returnStack[0]);
    }
    
    
    /**
      * @depends testLoadDepreciate
      */
    public function testLoadSet()
    {
        $io = $this->getMockBuilder('\Faker\Components\Config\Io')->disableOriginalConstructor()->getMock();    
        $ent = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
        
        
        $data = array();  
        $data[0] = array(
            'type'            => 'pdo_mysql',
            'schema'          => 'sakila',
            'user'            => 'root',
            'password'        => 'vagrant',
            'host'            => 'localhost',
            'port'            => '3306',
            'socket'          => false,
            'path'            => false,
            'memory'          => false,
            'charset'         => false,
            'connName'        => 'myconnectNameA',
            'readOnly'        => true
        );
        $data[1] = array(
            'type'            => 'pdo_mysqlB',
            'schema'          => 'sakilaB',
            'user'            => 'rootB',
            'password'        => 'vagrantB',
            'host'            => 'localhostB',
            'port'            => '3306B',
            'socket'          => false,
            'path'            => false,
            'memory'          => false,
            'charset'         => false,
            'connName'        => 'myconnectNameB',
            'readOnly'        => false
        );
       
        
        $io->expects($this->once())
           ->method('load') 
           ->with($this->equalTo('myconfig.php'),$this->equalTo(null))
           ->will($this->returnValue($data));
        
        $loader = new Loader($io);
        $returnStack = $loader->load('myconfig.php');
        
        $this->assertInternalType('array',$returnStack);
        $this->assertCount(2,$returnStack);
        $this->matchFakerDBEntity($data[0],$returnStack[0]);
        $this->matchFakerDBEntity($data[1],$returnStack[1]);
    }
    
}
/* End of File */