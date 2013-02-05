<?php
namespace Faker\Components\Engine\Original\Distribution;


use PHPStats\Generator\GeneratorInterface,
    PHPStats\PDistribution\ProbabilityDistributionInterface;


/**
  *  All Distribution Nodes should implement
  *
  *  @since 1.0.4
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  */
interface DistributionInterface
{
    
    /**
      *  Fetch the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function getGenerator();
    
    /**
      *  Set the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function setGenerator(GeneratorInterface $generator);
    
    
    /**
      *  Set the assigned distribution
      *
      *  @param HPStats\PDistribution\ProbabilityDistributionInterface
      *  @access public
      */
    public function setDistribution(ProbabilityDistributionInterface $dist);
    
    /**
      *  Return the assigned distribution
      *
      *  @return HPStats\PDistribution\ProbabilityDistributionInterface
      *  @access public
      */
    public function getDistribution();
    
    
    public function create();
    
    
}
/* End of File */