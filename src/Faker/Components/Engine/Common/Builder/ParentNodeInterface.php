<?php
namespace Faker\Components\Engine\Common\Builder;


use Faker\Components\Engine\Common\Composite\CompositeInterface;

/**
  *  Interface for Builders and Definitions allowing a composite
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface ParentNodeInterface extends NodeInterface
{
    /**
      *  Append a node to this one
      *
      *  @access public
      *  @return NodeInterface
      *  @param  Faker\Components\Engine\Common\Composite\CompositeInterface $node
      */
    public function append(CompositeInterface $node);
    
    /**
      *  Return this nodes children
      *
      *  @access public
      *  @return array[Faker\Components\Engine\Common\Composite\CompositeInterface]
      */
    public function children();
    
    
    
}
/* End of File */