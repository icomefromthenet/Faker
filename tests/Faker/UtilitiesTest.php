<?php
namespace Faker\Tests\Faker;

use Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;

class UtilitiesTest extends AbstractProject
{
    
    public function testgenerateRandomText()
    {
        $project = $this->getProject();
        $util = new Utilities($project);
        $words = array('word_1','word_2','word_3','word_4','word_5','word_6','word_7','word_8','word_9','word_10');
        $min = 2;
        $max = 8;
        $par = $util->generateRandomText($words,$min,$max,$project['random_generator']);

        $this->assertFalse(empty($par));
    }
    
    
}
/* End of File */