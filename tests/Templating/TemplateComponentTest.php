<?php
namespace Faker\Tests\Templating;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Templating\Entity,
    Faker\Components\Templating\Writer,
    Faker\Components\Templating\Loader,
    Faker\Components\Templating\TwigLoader,
    Faker\Components\Templating\Io as TemplateIO,
    Faker\Components\Templating\Template,
    Faker\Tests\Base\AbstractProject;  

class TemplateComponentTest extends AbstractProject
{
      public function testManagerLoader()
    {
        $project = $this->getProject();

        $manager = $project['template_manager'];

        $this->assertInstanceOf('Faker\Components\Templating\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['template_manager'];

        $this->assertSame($manager,$manager2);

    }


    public function testManagerGetLoader()
    {
        $project = $this->getProject();
        $manager = $project['template_manager'];

        $loader = $manager->getLoader();

        $this->assertInstanceOf('Faker\Components\Templating\Loader',$loader);

        # test the loader has IO object
        $this->assertInstanceOf('Faker\Components\Templating\Io',$loader->getIo());

        return $loader;
    }

    public function testTwigLoader()
    {
        $loader  = new TwigLoader(new TemplateIo($this->getProject()->getPath()->get()));

        $this->assertInstanceOf('\Faker\Components\Templating\TwigLoader',$loader);

        # test isfresh returns true (always fresh)
        $this->assertTrue($loader->isFresh('file',100));

        # test if the cache key hands back the argument unchanged (no cache)
        $this->assertSame('one',$loader->getCacheKey('one'));

        # make sure io properties work
        $this->assertInstanceOf('Faker\Components\Templating\Io',$loader->getIo());

        # test template load for valid file
        $template = $loader->getSourceContext('sql/mysql/header_template.twig');

        $this->assertNotEmpty($template);
    }

    /**
      *  @expectedException \Faker\Io\FileNotExistException
      */
    public function testFailMissingExtension()
    {
        $loader  = new TwigLoader(new TemplateIo($this->getProject()->getPath()->get()));
        $loader->getSourceContext('test_data');
    }

    /**
      *  @expectedException \Faker\Io\FileNotExistException
      */
    public function testFailMissingFile()
    {
        $loader  = new TwigLoader(new TemplateIo($this->getProject()->getPath()->get()));
        $loader->getSourceContext('crap_data.twig');
    }

    /**
      *  @depends testManagerGetLoader
      */
    public function testTemplateLoader(Loader $loader)
    {
        $template = $loader->load('sql/mysql/header_template.twig');

        $this->assertInstanceOf('\Faker\Components\Templating\Template',$template);

    }
    
    /**
      *  @depends testManagerGetLoader
      */
    public function testLoaderWithVars(Loader $loader)
    {
        $vars = array('one' => 1, 'two' => 2);
 
        $template = $loader->load('sql/mysql/header_template.twig',$vars);

        $this->assertInstanceOf('\Faker\Components\Templating\Template',$template);
    
        $this->assertSame($vars,$template->getData());   
 
        # test the setdata ob template
        $vars = array('one' => 1, 'two' => 2,'three' => 3);
        
        $template->setData($vars);
        
        $this->assertSame($vars,$template->getData());   
 
 
    }
    
    /**
      *  @depends testManagerGetLoader
      */
    public function testLoaderWithString(Loader $loader)
    {
        $vars = array('one' => 1, 'two' => 2);
 
        $template = $loader->loadString('{{one}}_{{two}}',$vars);

        $this->assertInstanceOf('\Faker\Components\Templating\Template',$template);
    
        $this->assertSame($vars,$template->getData());   
 
         $str =$template->render();     
         $this->assertEquals('1_2',$str);
    }

    /**
      *  @depends testManagerGetLoader
      *  @expectedException \Faker\Io\FileNotExistException
      */
    public function testTemplateLoaderExceptionMissingFile(Loader $loader)
    {
        $template = $loader->load('crap_data.twig');
    }


    /**
      *  @expectedException \Faker\Components\Templating\Exception
      */
    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['template_manager'];
        $writer = $manager->getWriter();
    }




}
/* End of File */
