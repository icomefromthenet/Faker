<?php
namespace Faker\Tests\Engine\Original;

use Faker\Components\Engine\Original\Utilities,
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