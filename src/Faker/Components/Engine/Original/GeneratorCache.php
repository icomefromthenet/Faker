<?php
namespace Faker\Components\Engine\Original;

use Iterator,
    Countable;

/*
 * class GeneratorCache used to cache generated values.
 *
 * A common iterator that will rewind when last value reached.
 * If we have 100 values cached but want to generate 1000 rows
 * thet automatic rewind is needed.
 *
 * @author lewis dyer
 * @version 1.0.2
 */

class GeneratorCache implements Iterator , Countable
{
    
    /*
     * __construct()
     *
     * @access public
     * @return void
     */
    
    public function __construct()
    {
        $this->position = 0;
    }
    
    //------------------------------------------------------------------
    # Iterator Interface

    
    /**
      *  @var the current position in cache 
      */
    protected $position = 0;
    
    /**
      *  @cache of values 
      */
    protected $values = array();  

   
    public function rewind()
    {
        $this->position = 0;
    }

    
    public function current()
    {
        return $this->values[$this->position];
    }

    
    public function key()
    {
        return $this->position;
    }

    
    public function next()
    {
        ++$this->position;
        
        # check if at the end then rewind.
        if($this->valid() === false) {
            $this->rewind();
        }
    }
    
    public function valid()
    {
        return isset($this->values[$this->position]);
    }
    
    
    //------------------------------------------------------------------
    # Countable Interface
    
    public function count()
    {
        return count($this->values);
    } 
    
    //------------------------------------------------------------------
    # Cache Functions
        
    public function add($value)
    {
        $this->values[] = $value;
    }
    
    public function purge()
    {
        $this->rewind();
        unset($this->values); // nuke the variable
        $this->values = array();
    }
    
    //------------------------------------------------------------------
    
}
/* End of File */