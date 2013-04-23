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
    
}
/* End of File */