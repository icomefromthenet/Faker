<?php
namespace Faker\Tests\Engine\Common\Selector;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\PositionManager;

class PositionManagerTest extends AbstractProject
{
    
    
    public function testPositionManager()
    {
        $manager = new PositionManager(5);
        
        $this->assertEquals(1,$manager->position());
        
        $manager->increment();
        
        $this->assertEquals(2,$manager->position());
        
        $manager->increment();
        
        $this->assertEquals(3,$manager->position());
        
        $manager->increment();
        
        $this->assertEquals(4,$manager->position());
        
        $this->assertFalse($manager->atLimit());
        
        $manager->increment();
        
        $this->assertEquals(5,$manager->position());
        
        $this->assertTrue($manager->atLimit());
        
        $manager->increment();
        
        $this->assertEquals(1,$manager->position());
        
        $manager->increment();
        
        $this->assertEquals(2,$manager->position());
        
        $manager->increment();
        
        $this->assertEquals(3,$manager->position());
        
        $manager->reset();
        
        $this->assertEquals(1,$manager->position());
        
        $manager->changeLimit(1);
        
        $manager->increment();
        
        $this->assertEquals(1,$manager->position());
        
        $manager->increment();
        
        $this->assertEquals(1,$manager->position());
    }
    
    
}
/* End of File */