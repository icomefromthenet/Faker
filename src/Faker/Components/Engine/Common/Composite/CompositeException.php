<?php
namespace Faker\Components\Engine\Common\Composite;

use Faker\Components\Engine\EngineException;

/**
  *  Exception used inside generator composites, contains a
  *  reference to the node that threw it.
  *
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class CompositeException extends EngineException
{
    /**
      *  @var CompositeInterface 
      */
    protected $node;
    
    
    public function __construct(CompositeInterface $node, $msg = '', $code = 0, \Exception $previous = null)
    {
        $this->node = $node;
        
        parent::__construct($msg,$code,$previous);
    }
    
    /**
      *   Return the CompositeInterface node that generated this original exception
      *
      *   @access public
      *   @return CompositeInterface
      */
    public function getNode()
    {
        return $this->node;
    }
    
    /**
      *  Create a path from the root node to the present node
      *
      *  @access public
      *  @return string the path
      */
    static function buildPath(CompositeInterface $node)
    {
        $paths = array();
        
        $paths[] = $node->getId();
        
        # build path from current node to root
        do{
            $node = $node->getParent();
            
            if($node instanceof CompositeInterface) {
                $paths[] = $node->getId();
            }
            
            
            
        } while($node instanceof CompositeInterface);
        
        
        # reverse path to get from schema to current node.
        array_reverse($paths);
        
        return $paths;
    }
    
}
/* End of File */
