<?php
namespace Faker\Components\Engine\Common\Composite;

use Doctrine\DBAL\Types\Type;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;


/**
  *  Interface allows nodes to be mapped into a formatter. The formatter
  *  requires list of DBAL Types which provided the DBALVisitor.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface DBALTypeInterface
{
    /**
      *  Return a DBAL Column Type
      *
      *  @return Doctrine\DBAL\Types\Type the column type.
      *  @access public
      */
    public function getDBALType();
    
    /**
      *  Return a DBAL Column Type 
      *
      *  @param string the column type name.
      *  @access public
      */
    public function setDBALType(Type $type);


}
/* End of File */
