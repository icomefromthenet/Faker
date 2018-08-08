<?php
namespace Faker\Tests\Engine\Common\Distribution;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Distribution\ExponentialDistribution;
    
class ExponentialDistributionTest extends AbstractProject
{
    
    public function testDistributionInterfaceProperties()
    {
        $generator = $this->createMock('PHPStats\Generator\GeneratorInterface');
        $basic     = $this->createMock('PHPStats\BasicStats');
        $lambda    = 0.5;
        $internal = $this->createMock('PHPStats\PDistribution\ProbabilityDistributionInterface');
        
        $dist = new ExponentialDistribution($generator,$basic,$lambda);
        
        $this->assertInstanceOf('PHPStats\Generator\GeneratorInterface',$dist);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\DistributionInterface',$dist);    
        
        # internal is the correct distribution
        $this->assertInstanceOf('PHPStats\PDistribution\Exponential',$dist->getInternal());
        
        # internal setter works with mock
        $dist->setInternal($internal);
        $this->assertEquals($internal,$dist->getInternal());
        
        
        
    }
    
    public function testGeneratorInterfaceProperties()
    {
        $generator = $this->createMock('PHPStats\Generator\GeneratorInterface');
        $basic     = $this->createMock('PHPStats\BasicStats');
        $lambda    = 0.5;
        $internal = $this->createMock('PHPStats\PDistribution\ProbabilityDistributionInterface');
        
        $generator->expects($this->at(0))
                  ->method('max')
                  ->with($this->equalTo(100));
        
        $generator->expects($this->at(1))
                  ->method('max')
                  ->will($this->returnValue(100));
        
        $generator->expects($this->at(2))
                  ->method('min')
                  ->with($this->equalTo(5));
        
        $generator->expects($this->at(3))
                  ->method('min')
                  ->will($this->returnValue(5));
                  
        $generator->expects($this->once())
                  ->method('seed')
                  ->with($this->equalTo(5000));
        
        $dist = new ExponentialDistribution($generator,$basic,$lambda);
        
        $dist->max(100);
        $this->assertEquals(100,$dist->max());
        
        $dist->min(5);
        $this->assertEquals(5,$dist->min());
        
        $dist->seed(5000);
        
    }
    
    
    public function testGenerate()
    {
        
        $generator = $this->createMock('PHPStats\Generator\GeneratorInterface');
        $basic     = $this->createMock('PHPStats\BasicStats');
        $lambda    = 0.5;
        $internal = $this->createMock('PHPStats\PDistribution\ProbabilityDistributionInterface');
        
        $generator->expects($this->at(0))
                  ->method('min')
                  ->with($this->equalTo(5));
                  
        $generator->expects($this->at(1))
                  ->method('max')
                  ->will($this->returnValue(100));
        
        $internal->expects($this->once())
                  ->method('rvs')
                  ->will($this->returnValue(10088));
                  
        $dist = new ExponentialDistribution($generator,$basic,$lambda);
        
        $dist->setInternal($internal);
        $this->assertEquals(10088,$dist->generate(5,100));
    }
    
}
/* End of File */