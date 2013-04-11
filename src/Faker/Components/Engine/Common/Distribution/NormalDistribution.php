<?php
namespace Faker\Components\Engine\Common\Distribution;

use PHPStats\Generator\GeneratorInterface;
use PHPStats\PDistribution\Normal;
use PHPStats\PCalculator\Normal as NormalCalculator;
use PHPStats\PDistribution\ProbabilityDistributionInterface;
use PHPStats\BasicStats;
use Faker\Components\Engine\Common\Distribution\DistributionInterface;

/**
  *  Normal Distribution bridge
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class NormalDistribution implements DistributionInterface 
{
    
    /**
      *  @var  PHPStats\Generator\GeneratorInterface
      */
    protected $internal;
    
    /**
      *  @var  PHPStats\BasicStats
      */
    protected $basicStats;
    
    /**
      *  @var the mu value 
      */
    protected $mu;
    
    /**
      *  @var the variance value 
      */
    protected $variance;
    
    /**
      *  @var PHPStats\PDistribution\Normal
      */
    protected $distribution;
        
    /**
      *  Class Constructor
      *
      *  @param PHPStats\Generator\GeneratorInterface $internalGnerator
      *  @param PHPStats\BasicStats $stats
      *  @param double $mu
      *  @param double $variance
      */    
    public function __construct(GeneratorInterface $internalGnerator, BasicStats $stats, $mu = 0.0, $variance = 1.0)
    {
        $this->internal     = $internalGnerator;
        $this->basicStats   = $stats;
        $this->variance     = $variance;
        $this->mu           = $mu;
        
        $this->distribution = $this->create();
    }
    
    
    /**
      *  Constructor function for the distribution 
      */
    protected function create()
    {
        return new Normal($this->mu,$this->variance,new NormalCalculator($this->internal,$this->basicStats));    
    }
    
    
    
     /**
      *  Generate a value between $min - $max
      *
      *  @param integer $max
      *  @param integer $max 
      */
    public function generate($min = 0,$max = null)
    {
        $this->internal->min($min);
        $this->internal->max($max);
        return $this->distribution->rvs();
    }
    
    /**
      *  Set the seed to use
      * 
      *  @param $seed integer the seed to use
      *  @access public
      */
    public function seed($seed = null)
    {
        $this->internal->seed($seed);
    }
    
    /**
      *  Return the highets possible random value
      *
      *  @access public
      *  @return double
      */
    public function max($value = null)
    {
        return $this->internal->max($value);
    }
    
    /**
      *  Return the smallest possible random value
      *
      *  @access public
      *  @return double
      */
    public function min($value = null)
    {
        return $this->internal->min($value);
    }
    
    /**
      *  Gets the internal php stats
      *
      *  @return PHPStats\PDistribution\ProbabilityDistributionInterface
      *  @access public
      */
    public function getInternal()
    {
        return $this->distribution;
    }
    
    /**
      *  Sets the internal phpstats distribution
      *
      *  @param PHPStats\PDistribution\ProbabilityDistributionInterface $internal
      *  @return void
      *  @access public
      */
    public function setInternal(ProbabilityDistributionInterface $internal)
    {
        $this->distribution = $internal;
    }
    
}
/* End of File */