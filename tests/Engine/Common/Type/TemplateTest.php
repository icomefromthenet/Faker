<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Template;
use Faker\Tests\Base\AbstractProject;


class TemplateTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');   
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
            
        $type = new Template($loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Template Type::File Does not Exists at
      */
    public function testConfigWithFile()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');   
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
            
        $type = new Template($loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('file','template1.twig');
        
        $this->assertTrue($type->validate());        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Template Type:: must set either a file or a template string
      */
    public function testFileMisssingAndTemplateStringMissing()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');   
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
            
        $type = new Template($loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
            
        $type->validate();
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Template Type::File Does not Exists at template1.twig
      */
    public function testGenerateBadFileLocation()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');   
        
        $template_loader  = $this->getMockBuilder('Faker\Components\Templating\Loader')
                                 ->disableOriginalConstructor()
                                 ->getMock();
        
        $io     = $this->createMock('Faker\Io\IoInterface');
        
        $io->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('template1.twig'))
            ->will($this->returnValue(false));
        
        $template_loader->expects($this->once())
                        ->method('getIo')
                        ->will($this->returnValue($io));
            
        $type = new Template($template_loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        $type->setOption('file','template1.twig');
        $type->validate(); 
    }
    
    public function testWithTemplateString()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');   
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
    
        $template = new Template($loader);
        $template->setGenerator($generator);
        $template->setLocale($locale);
        $template->setUtilities($utilities);
        
        $template->setOption('template','a template string {{ 1 + 1 }}');
        
        $this->assertTrue($template->validate());
        $this->assertEquals('a template string 2',$template->generate(1));
    }
    
    
    
}
/*End of file */
