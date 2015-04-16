<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\DatasourceRepository;
use Faker\Components\Engine\Common\Datasource\DatasourceBuilder;

class DatasourceBuilderTest extends AbstractProject
{
    
    public function testBuilderWithDefinition()
    {
        DatasourceRepository::registerExtension('example_source','Faker\Tests\Engine\Common\Datasource\Mock\MockDefinition');
        
        $repo = new DatasourceRepository();
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $gen    = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();     
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock(); 
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        
        $builder = new DatasourceBuilder($event,$utilities,$gen,$locale,$database,$template,$repo);   
       
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\DatasourceNode',$builder->createSource('example_source'));
        
    }
    
    
    public function testBuilderDatasourceOnly()
    {
       DatasourceRepository::registerExtension('example_source','Faker\Tests\Engine\Common\Datasource\Mock\MockDataSource');
        
        $repo = new DatasourceRepository();
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $gen    = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();     
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock(); 
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
        
        $builder = new DatasourceBuilder($event,$utilities,$gen,$locale,$database,$template,$repo);   
       
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\DatasourceNode',$builder->createSource('example_source'));
       
        
    }
    
    
    public function testErrorNoneFoundInRepo()
    {
        
        
    }
    
}
/* End of File */