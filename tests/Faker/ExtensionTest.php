<?php
namespace Faker\Tests\Faker;

use Faker\Components\Extension\Faker\Type\Demo,
    Faker\Tests\Base\AbstractProject;

class ExtensionTest extends AbstractProject
{
    
    public function testExtensionLoading()
    {
        $project = $this->getProject();
        
        $demo = new Demo();
        $this->assertInstanceOf('\Faker\Components\Extension\Faker\Type\Demo',$demo);
        
    }
    
    
}
/* End of File */