<?php
namespace Faker\Components\Engine\Common\Builder;

/**
  *  Interface for Builders and Definitions allowing a composite
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface NodeInterface
{
    
    /**
      *  Fetch the generator composite node managed by this builder node
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode();
    
    
    /**
    * Sets the parent node.
    *
    * @param ParentNodeInterface $parent The parent
    */
    public function setParent(NodeInterface $parent);
    
    /**
      *  Return the assigned parent
      *
      *  @param access
      *  @return NodeInterface
      */
    public function getParent();
    
    /**
    * Return the parent node and build the node
    * defined by this builder and append it to the parent.
    *
    * @return NodeInterface The builder of the parent node
    */
    public function end();
    
    
}
/* End of File */