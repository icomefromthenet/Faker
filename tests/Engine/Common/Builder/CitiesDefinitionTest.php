<?php
namespace Faker\Tests\Engine\Common\Builder;

use Faker\Components\Engine\Common\Type\Cities;
use Faker\Components\Engine\Common\Builder\CitiesTypeDefinition;
use Faker\Tests\Base\AbstractProject;

class CitiesDefinitionTest extends AbstractProject
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
            
        $type = new CitiesTypeDefinition();
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
        
        
            
        $type = new CitiesTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        $typeNode    = $type->countries(array('AU'))->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Type\Cities',$interalType);
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
        
        $type = new CitiesTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        $typeNode = $type->countries(array('AU','EU'))->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertEquals('AU|EU',$interalType->getOption('countries'));
 
        $this->assertEquals($generator,$interalType->getGenerator());
        $this->assertEquals($locale,$interalType->getLocale());
        $this->assertEquals($utilities,$interalType->getUtilities());
        
    }
    
}
/*End of file */
