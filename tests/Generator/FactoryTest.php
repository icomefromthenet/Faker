<?php
namespace Faker\Tests\Generator;

use Faker\Tests\Base\AbstractProject,
    Faker\Generator\GeneratorFactory;

class FactoryTest extends AbstractProject
{
    public function testSrandGenerator()
    {
        $factory = new GeneratorFactory();
        $this->assertInstanceOf('\Faker\Generator\SrandRandom',$factory->create('srand',null));
    }
    
    public function testSimpleGenerator()
    {
        $factory = new GeneratorFactory();
        $this->assertInstanceOf('\Faker\Generator\SimpleRandom',$factory->create('simple',null));
    }
    
    public function testMersenneGenerator()
    {
        $factory = new GeneratorFactory();
        $this->assertInstanceOf('\Faker\Generator\MersenneRandom',$factory->create('mersenne',null));
        
    }
    
}
/* End of File */