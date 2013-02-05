<?php
namespace Faker\Tests\Engine\Original;

use Faker\Components\Engine\Original\GeneratorCache,
    Faker\Tests\Base\AbstractProject;

class GeneratorCacheTest extends AbstractProject
{
    
    public function testNewInstance()
    {
        $cache = new GeneratorCache();
        
        $this->assertInstanceOf('\Faker\Components\Engine\Original\GeneratorCache',$cache);
        
    }
    
    
    public function testAddCache()
    {
        $cache = new GeneratorCache();
        
        $cache->add('value1');
        $cache->add('value2');
        $cache->add('value3');
        $cache->add('value4');
        $cache->add('value5');
        
        $this->assertEquals($cache->count(),5);
        $this->assertEquals(count($cache),5);
    }
    
    public function testCachePurge()
    {
        $cache = new GeneratorCache();
        
        $cache->add('value1');
        $cache->add('value2');
        $cache->add('value3');
        $cache->add('value4');
        $cache->add('value5');
        
        $cache->purge();
        $this->assertEquals($cache->count(),0);
        $this->assertEquals(count($cache),0);
        
    }
    
    
    public function testCacheIterate()
    {
        $cache = new GeneratorCache();
        
        $cache->add('value1');
        $cache->add('value2');
        $cache->add('value3');
        $cache->add('value4');
        $cache->add('value5');
        
        $this->assertEquals($cache->current(),'value1');
        $cache->next();        
        $this->assertEquals($cache->current(),'value2');
        $cache->next();        
        $this->assertEquals($cache->current(),'value3');
        $cache->next();        
        $this->assertEquals($cache->current(),'value4');
        $cache->next();        
        $this->assertEquals($cache->current(),'value5');
        
        
    }
    
    
    public function testCacheAutoRewind()
    {
        $cache = new GeneratorCache();
        
        $cache->add('value1');
        $cache->add('value2');
        $cache->add('value3');
        $cache->add('value4');
        $cache->add('value5');
        
        $this->assertEquals($cache->current(),'value1');
        $cache->next();        
        $this->assertEquals($cache->current(),'value2');
        $cache->next();        
        $this->assertEquals($cache->current(),'value3');
        $cache->next();        
        $this->assertEquals($cache->current(),'value4');
        $cache->next();        
        $this->assertEquals($cache->current(),'value5');
        $cache->next();        
        $this->assertEquals($cache->current(),'value1');
        $cache->next();
        $this->assertEquals($cache->current(),'value2');
    }
    
    
}
/* End of File */