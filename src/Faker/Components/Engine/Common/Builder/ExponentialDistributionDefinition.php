<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Distribution\ExponentialDistribution;

use PHPStats\BasicStats;

/**
  *  Definition for the Exponential Distribution Generator
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ExponentialDistributionDefinition extends AbstractDefinition
{
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $lambda       = 0.00;
        
        if(isset($this->attributes['lambda']) === true) {
            $lambda = $this->attributes['lambda'];
        }
        
        return new ExponentialDistribution($this->generator,new BasicStats(),$lambda);
    }
    
    
    public function lambda($mu)
    {
        $this->attribute('lambda',$mu);
        
        return $this;
    }
    
    

}
/* End of File */