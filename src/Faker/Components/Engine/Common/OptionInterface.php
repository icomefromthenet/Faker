<?php

namespace Faker\Components\Engine\Common;

use Symfony\Component\Config\Definition\ConfigurationInterface;

/*
 * interface OptionInterface and interface for setting extra options
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
interface OptionInterface extends ConfigurationInterface
{
    
    public function getOption($name);
    
    public function setOption($name,$value);
    
    public function hasOption($name);
    
}
/* End of File */