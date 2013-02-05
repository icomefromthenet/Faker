<?php
namespace Faker\Components\Engine\Original;

use Faker\Components\Engine\Original\Visitor\VisitorInterface,
    Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Visitor\BaseVisitor;

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