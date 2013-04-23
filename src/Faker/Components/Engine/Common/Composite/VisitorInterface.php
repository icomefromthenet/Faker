<?php
namespace Faker\Components\Engine\Common\Composite;

use Faker\Components\Engine\Common\Visitor\BasicVisitor;

/**
  *  Interface too add  visitor support to CompositeNodes.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface VisitorInterface
{
    /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BasicVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BasicVisitor $visitor);


}
/* End of File */
