<?php
namespace Faker\Tests\Engine\Common\Distribution;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Distribution\ExponentialDistributionDefinition;
use Faker\Components\Engine\Common\Distribution\NormalDistributionDefinition;
use Faker\Components\Engine\Common\Distribution\PoissonDistributionDefinition;
    
class DefinitionTest extends AbstractProject
{
    
    public function testExponentialCreate()
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
        
        
        $dist = new ExponentialDistributionDefinition();
        
        $dist->database($database);
        $dist->eventDispatcher($event);
        $dist->locale($locale);
        $dist->templateLoader($tempate);
        $dist->utilities($utilities);
        $dist->generator($generator);
        
        $dist->lambda(0.5);
        
        $node = $dist->getNode();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\ExponentialDistribution',$node);
        
    }
    
    
    public function testNormalCreate()
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
        
        
        $dist = new NormalDistributionDefinition();
        
        $dist->database($database);
        $dist->eventDispatcher($event);
        $dist->locale($locale);
        $dist->templateLoader($tempate);
        $dist->utilities($utilities);
        $dist->generator($generator);
        
        $dist->mu(0.5);
        $dist->variance(0.5);
        
        $node = $dist->getNode();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\NormalDistribution',$node);
        
    }
    
    
    public function testPoissonCreate()
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
        
        
        $dist = new PoissonDistributionDefinition();
        
        $dist->database($database);
        $dist->eventDispatcher($event);
        $dist->locale($locale);
        $dist->templateLoader($tempate);
        $dist->utilities($utilities);
        $dist->generator($generator);
        
        $dist->lambda(0.5);
        
        $node = $dist->getNode();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Distribution\PoissonDistribution',$node);
        
    }
  
}
/* End of File */