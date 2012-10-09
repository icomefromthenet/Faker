<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Template,
    Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;

class TemplateTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 't1';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new Template($id,$parent,$event,$utilities,$generator);
        
        $this->assertInstanceOf('\\Faker\\Components\\Faker\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfigWithFile()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new Template($id,$parent,$event,$utilities,$generator);
        $type->setOption('file','template1.twig');
        $type->setOption('name','template');
        $type->merge();        
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Template Type:: must set either a file or a template string
      */
    public function testFileMisssingAndTemplateStringMissing()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new Template($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','template');
        $type->merge();
        $type->validate();
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Template::File Does not Exists at template1.twig
      */
    public function testGenerateBadFileLocation()
    {
        $id = 'table_two';
        
        $template_loader  = $this->getMockBuilder('Faker\Components\Templating\Loader')
                                 ->disableOriginalConstructor()
                                 ->getMock();
        
        $template_manager = $this->getMockBuilder('Faker\Components\Templating\Manager')
                                 ->disableOriginalConstructor()
                                 ->getMock();
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale  = $this->getMock('\Faker\Locale\LocaleInterface');
        
        $io     = $this->getMock('Faker\Io\IoInterface');
        
        $io->expects($this->once())
            ->method('exists')
            ->with($this->equalTo('template1.twig'))
            ->will($this->returnValue(false));
        
        $template_loader->expects($this->once())
                        ->method('getIo')
                        ->will($this->returnValue($io));
        
                                 
        $template_manager->expects($this->once())
                         ->method('getLoader')
                         ->will($this->returnValue($template_loader));
        
        
        $utilities->expects($this->once())
                 ->method('getTemplatingManager')
                 ->will($this->returnValue($template_manager));
        
            
        $type = new Template($id,$parent,$event,$utilities,$generator);
        $type->setOption('file','template1.twig');
        $type->setOption('name','alphanumeric');
        $type->setLocale($locale);
        $type->merge();
        $type->validate(); 
    }
    
    public function testWithTemplateString()
    {
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $utilities = new Utilities($this->getProject());
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        
        $template = new Template('id_a',$parent,$event,$utilities,$generator);
        
        $template->setOption('name','template');
        $template->setOption('template','a template string {{ 1 + 1 }}');
        $template->merge();
        $template->validate();
        
        $this->assertEquals('a template string 2',$template->generate(1));
    }
    
    
    
}
/*End of file */
