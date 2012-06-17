<?php
namespace Faker\Components\Faker;

use Faker\Components\Faker\Visitor\VisitorInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Visitor\BaseVisitor;

/*
 * class BaseNode
 * Composite base classes inherit from this class
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */

abstract class BaseNode implements VisitorInterface
{
    /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BaseVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BaseVisitor $visitor)
    {
        throw new FakerException('not implemented');
    }
    
}
/* End of File */