<?php
namespace Faker\Tests\Engine\Entity;

use Faker\Components\Engine\Entity\EntityIterator;
use Faker\Components\Engine\Entity\GenericEntity;
use Faker\Tests\Base\AbstractProject;

class EntityIteratorTest extends AbstractProject
{
    
    
    public function testSetAmount()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  500;
        
        $iterator = new EntityIterator($amount,$composite);
        
        $this->assertEquals($amount,$iterator->getAmount());
        
        $iterator->setAmount(100);
        
        $this->assertEquals(100,$iterator->getAmount());
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage to generate must be an integer > 0
      */    
    public function testSetAmountNonIntegerError()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  'aaa';
        
        $iterator = new EntityIterator($amount,$composite);
        
    }
    
    
     /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage to generate must be an integer > 0
      */    
    public function testSetAmountZeroOrNegativeError()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  0;
        
        $iterator = new EntityIterator($amount,$composite);
        
    }
    
    
    public function testGeneratorCompositeProperty()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  100;
        
        $iterator = new EntityIterator($amount,$composite);
        
        $this->assertEquals($composite,$iterator->getGenerator());
        
    }
    
    
    public function testCacheSetters()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  100;
        
        $iterator = new EntityIterator($amount,$composite);
        
        $iterator->disableCache();
        $iterator->enableCache();
        
    }
    
    
    public function testImplementsIterator()
    {
        
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  100;
        
        $iterator = new EntityIterator($amount,$composite);
        $this->assertInstanceOf('\Iterator',$iterator);
        
    }
    
    
    public function testGenerateWithCacheNoMapper()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  5;
        
        $iterator = new EntityIterator($amount,$composite,null,true);
        
        
        $composite->expects($this->exactly(5))
                  ->method('generate')
                  ->will($this->returnValue(new GenericEntity()));
        
        # fill the cache
        foreach($iterator as $result) {
            $this->assertInstanceOf('Faker\Components\Engine\Entity\GenericEntity',$result);
        }
        
        # come from cache
        foreach($iterator as $result) {
            $this->assertInstanceOf('Faker\Components\Engine\Entity\GenericEntity',$result);
        }
        
    }
    
    public function testGenerateNoCacheNoMapper()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  5;
        
        $iterator = new EntityIterator($amount,$composite,null,true);
        
        $iterator->disableCache();
        
        $composite->expects($this->exactly(10))
                  ->method('generate')
                  ->will($this->returnValue(new GenericEntity()));
        
        # fill the cache
        foreach($iterator as $result) {
            $this->assertInstanceOf('Faker\Components\Engine\Entity\GenericEntity',$result);
        }
        
        # come from cache
        foreach($iterator as $result) {
            $this->assertInstanceOf('Faker\Components\Engine\Entity\GenericEntity',$result);
        }
        
    }
    
    
    public function testGenerateWithCacheWithMapper()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  5;
        
        $iterator = new EntityIterator($amount,$composite,function(GenericEntity $entity){return 1;},false);
        
        $iterator->enableCache();
        
        $composite->expects($this->exactly(5))
                  ->method('generate')
                  ->will($this->returnValue(new GenericEntity()));
        
        # fill the cache
        foreach($iterator as $result) {
            $this->assertEquals(1,$result);
        }
        
        # come from cache
        foreach($iterator as $result) {
            $this->assertEquals(1,$result);
        }
        
    }
    
    
     public function testGenerateNoCacheWithMapper()
    {
        $composite =  $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $amount    =  5;
        
        $iterator = new EntityIterator($amount,$composite,function(GenericEntity $entity){return 1;},false);
        
        
        $composite->expects($this->exactly(10))
                  ->method('generate')
                  ->will($this->returnValue(new GenericEntity()));
        
        # fill the cache
        foreach($iterator as $result) {
            $this->assertEquals(1,$result);
        }
        
        # come from cache
        foreach($iterator as $result) {
            $this->assertEquals(1,$result);
        }
        
    }
    
}
/*End of file */
