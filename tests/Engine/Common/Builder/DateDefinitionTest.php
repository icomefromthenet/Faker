<?php
namespace Faker\Tests\Engine\Common\Builder;

use Faker\Components\Engine\Common\Type\Date;
use Faker\Components\Engine\Common\Builder\DateTypeDefinition;
use Faker\Tests\Base\AbstractProject;

class DateDefinitionTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
            
        $type = new DateTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        
        $this->assertInstanceOf('\Faker\Components\Engine\Common\Builder\AbstractDefinition',$type);
    }
    
    
    public function testCreateType()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        
        
            
        $type = new DateTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        $typeNode = $type->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Type\Date',$interalType);
        $this->assertInstanceOf('\Faker\Components\Engine\Common\Composite\TypeNode',$typeNode);
        
    }
    
    
    public function testTypePropertiesSet()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        
        $type = new DateTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        $start  = new \DateTime();
        $max    = new \DateTime();
        $modify = '+ 1 hour';
        
        $typeNode = $type
            ->startDate($start)
                ->maxDate($max)
                ->modifyTime($modify)
                ->pickRandomBetweenMinMax()
            ->getNode(); 
        
        $interalType = $typeNode->getType();
        
        $oStartOption = new \DateTime($interalType->getOption('start'));
        $oMaxOption  = new \DateTime($interalType->getOption('max'));
        
        $this->assertEquals($start->format('d-m-Y'),$oStartOption->format('d-m-Y') );
        $this->assertEquals($max->format('d-m-Y'),$oMaxOption->format('d-m-Y'));
        
        $this->assertEquals(true,$interalType->getOption('random'));
        $this->assertEquals($modify,$interalType->getOption('modify'));

        $this->assertEquals($generator,$interalType->getGenerator());
        $this->assertEquals($locale,$interalType->getLocale());
        $this->assertEquals($utilities,$interalType->getUtilities());
        
    }
    
}
/*End of file */
