<?php
namespace Faker\Components\Engine\Common\Composite;

/**
  *  Builds A FQN Path to a node 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class PathBuilder
{

    /*
     * function buildPath
     * 
     * @param CompositeInterface $node
     * @return string a FQN
     * @access public
     */
    public function buildPath(CompositeInterface $node)
    {
        $path = array();
        
        do {
            # if ID is empty string uses its type to fill in gaps.
            if($node->getId() === '') {
                $path[] = get_class($node);
            } else {
                $path[] = $node->getId();    
            }
            
            $node   = $node->getParent();
            
        } while($node !== null);
        
        return implode('.',$path);
    }
    
}
/* End of File */