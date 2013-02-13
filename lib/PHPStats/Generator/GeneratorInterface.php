<?php
namespace PHPStats\Generator;

/**
  *  Interface that all generators should implement
  *
  *  @access Lewis Dyer <getintouch@icomefromthenet.com>
  */
interface GeneratorInterface
{
    
    /**
      *  Generate a value between $min - $max
      *
      *  @param integer $max
      *  @param integer $max 
      */
    public function generate($min = 0,$max = null);
    
    /**
      *  Set the seed to use
      * 
      *  @param $seed integer the seed to use
      *  @access public
      */
    public function seed($seed = null);
    
    /**
      *  Return or Set the highest possible random value
      *
      *  @access public
      *  @return double
      *  @param numeric $value
      */
    public function max($value = null);
    
    /**
      *  Return or Set the lowest  possible random value
      *
      *  @access public
      *  @return double
      *  @param numeric $value
      */
    public function min($value = null);
    
    
}
/* End of File */