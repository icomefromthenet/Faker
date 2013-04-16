<?php
namespace Faker\Tests\Engine\Common\Distribution;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Distribution\NormalDistribution;
    
class NormalDistributionTest extends AbstractProject
{
    
    public function testDistributionInterfaceProperties()
    {
        $generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $basic     = $this->getMock('PHPStats\BasicStats');
        $internal = $this->getMock('PHPStats\PDistribution\ProbabilityDistributionInterface');
        
        $dist = new NormalDistribution($generator,$basic);
        
        $this->assertInstanceOf('PHPStats\Generator\GeneratorInterface',$dist);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\DistributionInterface',$dist);    
        
        # internal is the correct distribution
        $this->assertInstanceOf('PHPStats\PDistribution\Normal',$dist->getInternal());
        
        # internal setter works with mock
        $dist->setInternal($internal);
        $this->assertEquals($internal,$dist->getInternal());
    }
    
    public function testGeneratorInterfaceProperties()
    {
        $generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $basic     = $this->getMock('PHPStats\BasicStats');
        $internal = $this->getMock('PHPStats\PDistribution\ProbabilityDistributionInterface');
        
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
        
        $dist = new NormalDistribution($generator,$basic);
        
        $dist->max(100);
        $this->assertEquals(100,$dist->max());
        
        $dist->min(5);
        $this->assertEquals(5,$dist->min());
        
        $dist->seed(5000);
        
    }
    
    
    public function testGenerate()
    {
        
        $generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $basic     = $this->getMock('PHPStats\BasicStats');
        $internal = $this->getMock('PHPStats\PDistribution\ProbabilityDistributionInterface');
        
        $generator->expects($this->at(0))
                  ->method('min')
                  ->with($this->equalTo(5));
                  
        $generator->expects($this->at(1))
                  ->method('max')
                  ->will($this->returnValue(100));
        
        $internal->expects($this->once())
                  ->method('rvs')
                  ->will($this->returnValue(10088));
                  
        $dist = new NormalDistribution($generator,$basic);
        
        $dist->setInternal($internal);
        $this->assertEquals(10088,$dist->generate(5,100));
    }
    
}
/* End of File */