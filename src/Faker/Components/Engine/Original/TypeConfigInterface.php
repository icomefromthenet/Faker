<?php
namespace Faker\Components\Engine\Original;

use Faker\Components\Engine\Common\OptionInterface;


interface TypeConfigInterface extends OptionInterface 
{
    
    public function getUtilities();
    
    public function setUtilities(Utilities $util);
    
}
/* End of File */