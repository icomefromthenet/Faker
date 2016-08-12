<?php
namespace Faker\Components\Engine\Common\Composite;


/**
  *  Interface to serialize the composite
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface SerializationInterface
{
    
    /**
      *  Convert the composite to XML Format
      *
      *  @access public
      *  @return string the serialize node
      */
    public function toXml();
    
    /**
     * Convert the composite to PHP Seeds Classes
     * 
     * This generate the text but must be written by to the FS by other process.
     * 
     * @param array of classes (as text) index by namesapce 
     */ 
    public function toPHP(array $aCode);
    
    
}
/* End of File */