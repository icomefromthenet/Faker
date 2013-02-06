<?php
namespace Faker\Components\Engine\Common;

use InvalidArgumentException;
use PHPStats\Generator\GeneratorInterface;

/*
 * Manage the position of an index using a cylindrical list
 *
 * ASSUMES 1 as first element ie (one based array)
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class PositionManager
{

    /**
     * The write limit
     *
     * @var integer
     */
    protected $writeLimit;

    /**
     * The current position
     *
     * @var integer
     */
    protected $currentAt = 1;

    //-------------------------------------------------------------
    /**
     * Class Constructor
     *
     * @param integer $limit the write limit
     * @return void
     */
    public function __construct($limit)
    {
        $this->changeLimit($limit);    
    }

    //-------------------------------------------------------------
    /**
     * Increment
     *
     * Increases the current postion by 1
     * unless position is at the limit where resets the poistion
     *
     * @return integer the current position
     */
    public function increment()
    {
        if($this->atLimit()) {
            return $this->reset();    
        }
        
        return ++$this->currentAt; 
    }

    //--------------------------------------------------------------
    /**
     * Deincrement
     *
     * Reduces the current postion by 1 unless
     * the postion at 0 where it shifts the position to the limit
     *
     * @return integer the current position
     */
    public function deincrement()
    {
        if($this->currentAt === 1) {
            return $this->currentAt = $this->writeLimit;           
        }
        
        return --$this->currentAt;
    }

    //--------------------------------------------------------------
    /**
     * Reset
     *
     * Changes the current position to 0
     *
     * @return void
     */
    public function reset()
    {
        return $this->currentAt = 1;
    }

    //-------------------------------------------------------------
    /**
     * At Limit
     *
     * Test if the current position equals or exceed the maxium
     *
     * @return boolean
     */
    public function atLimit()
    {
        return ($this->currentAt >= $this->writeLimit )? true : false;
    }

    
    /**
      *  Change the limit
      *
      *  @param integer $limit use null to set no limit
      */
    public function changeLimit($limit)
    {
        if (is_integer($limit) === false || (integer) $limit < 0) {
            throw new InvalidArgumentException('Limit must be and integer and >= 0 ');
        }   
        
        $this->writeLimit = $limit;
        $this->reset();
    }
    
    /**
      *  Return the current position
      *
      *  @access public
      *  @return integer the current position
      */
    public function position()
    {
        return $this->currentAt;
    }
}
/* End of File */
