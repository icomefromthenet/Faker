<?php
namespace Faker\Components\Engine\Common\Distribution;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;


use PHPStats\BasicStats;

/**
  *  Definition for the Normal Distribution Generator
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class NormalDistributionDefinition extends AbstractDefinition
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
        
        $mu       = 0.00;
        $variance = 1.0;
        
        
        if(isset($this->attributes['mu']) === true) {
            $mu = $this->attributes['mu'];
        }
        
        if(isset($this->attributes['variance']) === true) {
            $variance = $this->attributes['variance'];
        }
        
        $type = new NormalDistribution($this->generator,new BasicStats(),$mu,$variance);
        
        
        return $type;
    }
    
    
    public function mu($mu)
    {
        $this->attribute('mu',$mu);
        
        return $this;
    }
    
    
    public function variance($var)
    {
        $this->attribute('variance',$var);
        
        return $this;
    }



}
/* End of File */