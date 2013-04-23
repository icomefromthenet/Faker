<?php
namespace Faker\Components\Engine\Common\Formatter;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\FormatterNode;
use Faker\Components\Engine\Common\Formatter\Phpunit;


/**
  *  Definition for the phpunit Formatter 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class PhpunitFormatterDefinition extends AbstractDefinition
{
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\FormatterNode The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        # assign mysql as a default platform
        if($this->dbalPlatform === null) {
            $this->dbalPlatform = 'mysql';
        }
        
        $platform  = $this->platformFactory->create($this->dbalPlatform);
        $formatter = $this->formatterFactory->create('phpunit',$platform,$this->attributes);
        
        # return a CompositeInterface Node
        return new FormatterNode('formatterNode',$this->eventDispatcher,$formatter);
    }
    
}
/* End of File */