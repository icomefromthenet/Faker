<?php
namespace Faker\Components\Writer;

use Faker\Components\Writer\Exception as WriterException;

class Limit
{

    /**
     * The write limit
     *
     * @var integer
     */
    protected $write_limit = null;

    /**
     * The current position
     *
     * @var integer
     */
    protected $current_at = 0;

    //-------------------------------------------------------------
    /**
     * Class Constructor
     *
     * @param integer $limit the write limit
     * @return void
     */
    public function __construct($limit)
    {

        if (is_null($limit) !== TRUE) {
            
            if (is_integer($limit) === FALSE) {
            throw new \InvalidArgumentException('Write limit must be and integer');
            }
    
            if ($limit < 0) {
                throw new \InvalidArgumentException('Write limit must be above zero');
            }
        }

        $this->write_limit = $limit;
        $this->current_at = 0;
    }

    //-------------------------------------------------------------
    /**
     * Increment
     *
     * Increases the current postion by 1
     *
     * @return boolean
     */
    public function increment()
    {
        if($this->write_limit !== NULL) {
            $this->current_at = $this->current_at + 1;
        }

        return TRUE;
    }

    //--------------------------------------------------------------
    /**
     * Deincrement
     *
     * Reduces the current postion by 1;
     *
     * @return boolean
     */
    public function deincrement()
    {
        if($this->write_limit !== NULL) {

            if($this->current_at > 0){
                $this->current_at = $this->current_at + -1;
                return TRUE;
            }

            return FALSE;

        }

        return TRUE;
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
        $this->current_at = 0;
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

        if($this->write_limit !== NULL) {
            return ($this->current_at >= $this->write_limit )? TRUE : FALSE;
        }

        return FALSE;
    }

    //---------------------------------------------------------------
    
    /**
      *  Change the limit
      *
      *  @param integer $limit use null to set no limit
      */
    public function changeLimit($limit)
    {
        if($limit !== null) {
            if(is_integer($limit) === false){
                throw new WriterException('Limit must be an integer');            
            }    
        }
        
        $this->write_limit = $limit;
        $this->reset();
    }

}
/* End of File */
