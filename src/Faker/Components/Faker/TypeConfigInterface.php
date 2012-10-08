<?php
namespace Faker\Components\Faker;

use Faker\Components\Faker\OptionInterface;


interface TypeConfigInterface extends OptionInterface 
{
    
    public function getUtilities();
    
    public function setUtilities(Utilities $util);
    
}
/* End of File */