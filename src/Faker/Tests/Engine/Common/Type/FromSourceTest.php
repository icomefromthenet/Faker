<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\FromSource;
use Faker\Tests\Base\AbstractProject;


class FromSourceTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
            
        $type = new FromSource($loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\BindDataInterface',$type);
    }
    
    //--------------------------------------------------------------------------
    # config errors
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Template Type::File Does not Exists at
      */
    public function testConfigWithFile()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
            
        $type = new FromSource($loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('file','template1.twig');
        $type->setOption('source','{source_name}');
        
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
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
            
        $type = new FromSource($loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('source','{source_name}');
            
        $type->validate();
    }
    
     /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage The child node "source" at path "config" must be configured
      */
    public function testErrorSourceMissing()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
            
        $type = new FromSource($loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('template','a template string {{ 1 + 1 }}');
            
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
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $template_loader  = $this->getMockBuilder('Faker\Components\Templating\Loader')
                                 ->disableOriginalConstructor()
                                 ->getMock();
        
        $io     = $this->getMock('Faker\Io\IoInterface');
        
        $io->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('template1.twig'))
            ->will($this->returnValue(false));
        
        $template_loader->expects($this->once())
                        ->method('getIo')
                        ->will($this->returnValue($io));
            
        $type = new FromSource($template_loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        $type->setOption('file','template1.twig');
        $type->setOption('source','{source_name}');
        $type->validate(); 
    }
    
    //--------------------------------------------------------------------------
  
    
    public function testWithTemplateString()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $loader    = $this->getProject()->getTemplatingManager()->getLoader();
    
        $template = new FromSource($loader);
        $template->setGenerator($generator);
        $template->setLocale($locale);
        $template->setUtilities($utilities);
        
        $template->setOption('template','a template string {{ 1 + 1 }}');
        $template->setOption('source','{source_name}');
        
        $this->assertTrue($template->validate());
        $this->assertEquals('a template string 2',$template->generate(1));
    }
    
    public function testboundDataPassedToTemplate()
    {
        $dataToBind = array('datatobind'=> 'value');
        $values     = array('values');
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $templateInstance = $this->getMockBuilder('Faker\Components\Templating\Template')
                          ->setMethods(array('render'))
                          ->disableOriginalConstructor()
                          ->getMock(); 
                          
        $template_loader  = $this->getMockBuilder('Faker\Components\Templating\Loader')
                           ->disableOriginalConstructor()
                           ->getMock();
        
        $templateInstance->expects($this->once())
                         ->method('render')
                         ->with($this->equalTo(array('values'=>$values,'sources'=>$dataToBind))); 
        
        
        $template_loader->expects($this->once())
                        ->method('loadString')
                        ->will($this->returnValue($templateInstance));
            
            
        $type = new FromSource($template_loader);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        $type->setOption('template','{tmpvar}');
        $type->setOption('source','source_name');
        
        $type->bindData($dataToBind);
        
        $type->validate(); 
        $type->generate(1,$values);
        
    }
    
}
/*End of file */

