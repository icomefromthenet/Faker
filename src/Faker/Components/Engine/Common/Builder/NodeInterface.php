<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;

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
    public function getNode($id,CompositeInterface $parent);
    
    
    /**
    * Sets the parent node.
    *
    * @param ParentNodeInterface $parent The parent
    */
    public function setParent(NodeInterface $parent);
    
    
    /**
    * Returns the parent node.
    *
    * @return NodeInterface The builder of the parent node
    */
    public function end();
    
    
}
/* End of File */