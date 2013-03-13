<?php
namespace Faker\Components\Engine\Common\Builder;

/**
  *  Interface for Builders and Definitions allowing a composite
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface ParentNodeInterface extends NodeInterface
{
    
    /**
      *  Return the assigned parent
      *
      *  @param access
      *  @return NodeInterface
      */
    public function getParent();
    
    /**
      *  Append a node to this one
      *
      *  @access public
      *  @return NodeInterface
      *  @param NodeInterface $node
      */
    public function append(NodeInterface $node);
    
    /**
      *  Return this nodes children
      *
      *  @access public
      *  @return array[NodeInterface]
      */
    public function children();
    
}
/* End of File */