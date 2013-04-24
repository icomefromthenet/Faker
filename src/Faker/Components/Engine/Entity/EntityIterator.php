<?php
namespace Faker\Components\Engine\Entity;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;

/**
  *  Iterate over results one entity at a time
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class EntityIterator implements \Iterator 
{

    protected $amount;
    
    protected $generator;
    
    protected $mapClosure;

    protected $results;

    protected $cache;
    
    protected $position;
    
    public function __construct($amount,GeneratorInterface $generator,\Closure $mapper = null, $cache = false)
    {
        $this->results    = array();
        $this->setAmount($amount);
        
        if(!is_bool($cache)) {
            throw new EngineException('to cache must be a boolean');
        }
        
        if($cache === true) {
            $this->enableCache();
        } else {
            $this->disableCache();    
        }
        
        $this->generator  = $generator;
        $this->mapClosure = $mapper;
    }
    
    //------------------------------------------------------------------
    # Iterator

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        $result = null;
        
        # pull result off the cache if exists
        if(isset($this->results[$this->position])) {
            
            $result = $this->results[$this->position];
            
        } else {
         
            $values = array();
            # ask generator to make use a generic result
            $result = $this->generator->generate($this->position,$values);
            
            # run the map closure to get final entity
            if($this->mapClosure instanceof \Closure) {
                $result = call_user_func($this->mapClosure,$result);    
            }
            
            
            # if cache enable add it
            if($this->cache === true) {
                $this->results[$this->position] = $result;
            }
        }
        
        return $result;
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return $this->position < $this->amount;
    }

    //------------------------------------------------------------------
    # Cache Mgt
    
    /**
      *  Enable the result cache
      *
      *  @access public
      */
    public function enableCache()
    {
        $this->cache = true;
    }
    
    /**
      *  Disable and clear the result cached
      *
      *  @access public
      */
    public function disableCache()
    {
        $this->cache   = false;
        unset($this->results);
        $this->results = array();
    }
    
    //------------------------------------------------------------------
    # Properties
    
    /**
      *  Fetch the to generate amount
      *
      *  @access public
      *  @return integer the am to generate
      */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
      *  Sets the to generate amount
      *
      *  @access public
      *  @param integer $amount
      */
    public function setAmount($amt)
    {
        if(!is_integer($amt)) {
            throw new EngineException('to generate must be an integer > 0');
        }
        
        if($amt < 1) {
            throw new EngineException('to generate must be an integer > 0');
        }
        
        $this->amount = (integer) $amt;
    }
    
    /**
      *  Fetch the value generator
      *
      *  @access public
      *  @return GeneratorInterface
      */
    public function getGenerator()
    {
        return $this->generator;    
    }

}
/* End of File */