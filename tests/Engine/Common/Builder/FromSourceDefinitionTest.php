<?php
namespace Faker\Tests\Engine\Common\Builder;

use Faker\Components\Engine\Common\Type\FromSource;
use Faker\Components\Engine\Common\Builder\FromSourceTypeDefinition;
use Faker\Tests\Base\AbstractProject;

class FromSourceDefinitionTest extends AbstractProject
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
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();  
            
        $type = new FromSourceTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        $type->templateLoader($template);
        
        
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
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();
        
            
        $type = new FromSourceTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        $type->templateLoader($template);
        
        $typeNode = $type->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Type\FromSource',$interalType);
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
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();
        
        $type = new FromSourceTypeDefinition();
        $type->eventDispatcher($event);
        $type->database($database);
        $type->locale($locale);
        $type->utilities($utilities);
        $type->generator($generator);
        $type->templateLoader($template);
        
        $str = '{{field1}}';
        $tpl = 'atemplate.twig';
        $sourceId = 'mysourceID';
        
        $typeNode = $type->useTemplateFile($tpl)->useTemplateString($str)->useDatasource($sourceId)->getNode();
        $interalType = $typeNode->getType();
        
        $this->assertEquals($tpl,$interalType->getOption('file'));
        $this->assertEquals($str,$interalType->getOption('template'));
        $this->assertEquals($sourceId,$interalType->getOption('source'));

        $this->assertEquals($generator,$interalType->getGenerator());
        $this->assertEquals($locale,$interalType->getLocale());
        $this->assertEquals($utilities,$interalType->getUtilities());
        
    }
    
    
    
}
/*End of file */
