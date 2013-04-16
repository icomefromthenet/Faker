<?php
namespace Faker\Tests\Engine\Common\Distribution;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Distribution\DistributionBuilder;
    
class DistributionBuilderTest extends AbstractProject
{
    
    public function testDistributionFactory()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                           ->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')
                          ->disableOriginalConstructor()
                          ->getMock();
        $tempate   = $this->getMockBuilder('Faker\Components\Templating\Loader')
                           ->disableOriginalConstructor()
                           ->getMock();       
        
        
        $dist = new DistributionBuilder($event,$utilities,$generator,$locale,$database,$tempate);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\ExponentialDistributionDefinition',$dist->exponentialDistribution());
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\NormalDistributionDefinition',$dist->normalDistribution());
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\PoissonDistributionDefinition',$dist->poissonDistribution());
        
    }
  
}
/* End of File */