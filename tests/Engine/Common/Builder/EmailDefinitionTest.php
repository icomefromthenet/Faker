<?php
namespace Faker\Tests\Engine\Common\Builder;

use Faker\Components\Engine\Common\Type\Email;
use Faker\Components\Engine\Common\Builder\EmailTypeDefinition;
use Faker\Tests\Base\AbstractProject;

class EmailDefinitionTest extends AbstractProject
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
            
        $type = new EmailTypeDefinition();
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
        
        
            
        $type = new EmailTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        $typeNode = $type->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Type\Email',$interalType);
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
        
        $type = new EmailTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        
        
        $format  = '{fname}{lname}{alpha1}@{alpha2}.{domain}';
        $domains = array('com.au','au');
        $params   = array('custom1' => 1);
        
        $typeNode = $type->format($format)
                         ->domains($domains)
                         ->params($params)
                         ->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertEquals($format,$interalType->getOption('format'));
        $this->assertEquals($domains,$interalType->getOption('domains'));
        $this->assertEquals(json_encode($params),$interalType->getOption('params'));

        $this->assertEquals($generator,$interalType->getGenerator());
        $this->assertEquals($locale,$interalType->getLocale());
        $this->assertEquals($utilities,$interalType->getUtilities());
        
    }
    
}
/*End of file */
