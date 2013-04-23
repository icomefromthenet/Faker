<?php
namespace Faker\Components\Engine\Common\Composite;

use Faker\Components\Engine\Common\GeneratorCache;

/**
  *  Interface for Generators Nodes
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface GeneratorInterface 
{

    /**
      *  Generate a value
      *
      *  @param integer $rows the current row number
      *  @param mixed $array list of values generated in context
      */
    public function generate($rows,$values = array());
    
    
    /**
      *  Sets the Generator Result Cache
      *
      *  @access public
      *  @param GeneratorCache $cache
      */
    public function setResultCache(GeneratorCache $cache);
    
    /**
      *  Return the Generator Result Cache
      *
      *  @access public
      *  @return GeneratorCache
      */
    public function getResultCache();
    
}
/* End of File */


    