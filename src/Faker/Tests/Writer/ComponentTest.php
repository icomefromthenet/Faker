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
        
        $conn   = $this->getMockBuilder('Doctrine\\DBAL\\Connection')->disableOriginalConstructor()->getMock();
        
        $databaseStream = $manager->getDatabaseStream($platform,$formatter,$conn);
        
        # test if database works
        $this->assertInstanceOf('Faker\\Components\\Writer\\DatabaseStream',$databaseStream);
        
        $this->assertSame($conn,$databaseStream->getDatabase());
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
