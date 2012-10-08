<?php   
namespace Faker\Text;

use \Iterator,
    \Faker\Text\SimpleStringInterface;

/*
 * class StringIterator
 *
 * Iterate of the string contained in a SimpleStringInterface
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.3
 */

class StringIterator implements Iterator
{
    
    /**
      *  @var SimpleStringInterface the assigned string 
      */
    protected $str;
    
    /**
      *  @var integer the current string position 
      */
    protected $pos;
    
    /*
     * __construct()
     * @param $arg
     */
    
    function __construct(SimpleStringInterface $str)
    {
        $this->str = $str;
        $this->pos = 0;
    }
    
    
    public function rewind()
    {
        $this->pos = 0;
    }

    public function current()
    {
        return $this->str->charAt($this->pos);
    }

    public function key()
    {
        return $this->pos;
    }

    public function next()
    {
        ++$this->pos;
    }

    
    public function valid()
    {
        if($this->pos < $this->str->length()) {
            return true;
        }
        return false;
    }
    
    /**
      *  Peek ahead to next character not effect iterator
      *
      *  @return String the next character | null if end of string
      *  @access public
      */    
    public function peek()
    {
        $peek = null;
        
        if(($this->pos +1) < $this->str->length()) {
            $peek = $this->str->charAt($this->pos + 1);
        }
        
        return $peek;
    }
    
}
/* End of File */