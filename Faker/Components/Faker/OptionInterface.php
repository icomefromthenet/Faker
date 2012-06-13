<?php

namespace Faker\Components\Faker;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/*
 * interface OptionInterface and interface for setting extra options
 */
interface OptionInterface extends ConfigurationInterface
{
    
    public function getOption($name);
    
    public function setOption($name,$value);
    
    public function merge($config);
    
}
/* End of File */