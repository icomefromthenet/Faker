<?php
namespace Faker\Components\Engine\Common\Distribution;

use PHPStats\PDistribution\ProbabilityDistributionInterface;
use PHPStats\Generator\GeneratorInterface;

/**
  *  Interface for a distribution generator
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface DistributionInterface extends GeneratorInterface
{
    /**
      *  Gets the internal php stats
      *
      *  @return PHPStats\PDistribution\ProbabilityDistributionInterface
      *  @access public
      */
    public function getInternal();
    
    /**
      *  Sets the internal phpstats distribution
      *
      *  @param PHPStats\PDistribution\ProbabilityDistributionInterface $internal
      *  @return void
      *  @access public
      */
    public function setInternal(ProbabilityDistributionInterface $internal);
    
    
}

/* End of File */