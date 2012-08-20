<?php
namespace Faker\Tests\Writer;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Writer\Writer,
    Faker\Components\Writer\Io as TemplateIO,
    Faker\Components\Writer\Cache,
    Faker\Components\Writer\Limit,
    Faker\Components\Writer\Sequence,
    Faker\Tests\Base\AbstractProject;

class ComponentTest extends AbstractProject
{
      /**
        *  @group Writer 
        */
      public function testManagerLoader()
      {
        $project = $this->getProject();

        $manager = $project['writer_manager'];

        $this->assertInstanceOf('Faker\Components\Writer\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['writer_manager'];

        $this->assertSame($manager,$manager2);
      }

    /**
      *  @group Writer 
      */
    public function testNewEncoder()
    {
        $project = $this->getProject();
        $manager = $project->getWritterManager();
      
        $this->assertInstanceOf('Faker\Components\Writer\Encoding',$manager->getEncoder('utf-8','utf-8'));
      
    }

    
    /**
      *  @group Writer 
      */
    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['writer_manager'];
        $platform = 'mysql';
        $formatter = 'sql';
        
        $writer = $manager->getWriter($platform,$formatter);

        $this->assertInstanceOf('Faker\Components\Writer\Writer',$writer);
        
        # test that stream was set
        $this->assertInstanceOf('Faker\Components\Writer\Stream',$writer->getStream());
        
        # test that cache was set
        $this->assertInstanceOf('Faker\Components\Writer\Cache',$writer->getCache());
       
         # test the loader has IO object
        $this->assertInstanceOf('Faker\Components\Writer\Io',$writer->getStream()->getIo());

        # test if a sequence object was supplied
        $this->assertInstanceOf('Faker\Components\Writer\Sequence',$writer->getStream()->getSequence());
        
        # test if a limit object was suppiled
        $this->assertInstanceOf('Faker\Components\Writer\Limit',$writer->getStream()->getLimit());
       
        
        # test if a header template was supplied
        $this->assertInstanceOf('Faker\Components\Templating\Template',$writer->getStream()->getHeaderTemplate());
       
        # test if a footer template was supplied
        $this->assertInstanceOf('Faker\Components\Templating\Template',$writer->getStream()->getFooterTemplate());
        
        
    }

     
      /**
        *  @group Writer 
        */
      public function testWriterWrite()
      {
        $project = $this->getProject();
        $manager = $project['writer_manager'];
        $platform = 'mysql';
        $formatter = 'sql';
        $writer = $manager->getWriter($platform,$formatter);
        
        $writer->write('line');
        $writer->write('line');
        $writer->write('line');
        $writer->write('line');
        $writer->write('line');
        $writer->write('line');
        
        $writer->flush();
            
      }
}
/* End of File */
