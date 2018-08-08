<?php
namespace Faker\Tests\Engine\Common;

use Faker\Components\Engine\Common\Utilities,
    Faker\Tests\Base\AbstractProject;

class UtilitiesTest extends AbstractProject
{
    
    public function testRandomAlphanumeric()
    {
        $project = $this->getProject();
        $util    = new Utilities();
        $locale  = $project->getLocaleFactory()->create('en');
        $random  = $project->getDefaultRandom();
        
        $par = $util->generateRandomAlphanumeric('ccCCC',$random,$locale);

        $this->assertNotEmpty($par);
    }
    
    
}
/* End of File */