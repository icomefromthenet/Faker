<?php
namespace Faker\Tests\Engine\Common;

use Faker\Extension\Faker\Type\Demo,
    Faker\Tests\Base\AbstractProject;

class ExtensionTest extends AbstractProject
{
    
    public function testExtensionLoading()
    {
        $project = $this->getProject();
        
        $id = 'when_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $gen    = $this->createMock('PHPStats\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $demo = new Demo($id,$parent,$event,$utilities,$gen);
        $this->assertInstanceOf('\Faker\Extension\Faker\Type\Demo',$demo);
        
    }
    
    
}
/* End of File */