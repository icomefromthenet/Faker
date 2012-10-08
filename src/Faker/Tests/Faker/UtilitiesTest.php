<?php
namespace Faker\Tests\Faker;

use Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;

class UtilitiesTest extends AbstractProject
{
    
    public function testRandomAlphanumeric()
    {
        $project = $this->getProject();
        $util    = new Utilities($project);
        $locale  = $project->getLocaleFactory()->create('en');
        $random  = $project->getDefaultRandom();
        
        $par = $util->generateRandomAlphanumeric('ccCCC',$random,$locale);

        //$this->assertFalse();
    }
    
    
}
/* End of File */