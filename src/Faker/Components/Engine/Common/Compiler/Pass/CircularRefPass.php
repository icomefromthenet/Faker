<?php
namespace Faker\Components\Engine\Common\Compiler\Pass;

use Faker\Components\Engine\Common\Compiler\CompilerPassInterface;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\EngineException as FakerException;

/*
 * Does A Circular Reference Check using DirectedGraph
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class CircularRefPass implements CompilerPassInterface
{
    /**
      *  @string id of the current node checking for
      */
    protected $current_id;
    
    /**
      *  @mixed string[] map of the current path ( inner-node -> outter-node -> reallyoutter-node) 
      */
    protected $current_path = array();
    
    
    /**
      *  Will Check the relationships for Circular References 
      *
      *  The References should be valid as
      *  missing ones will not cause error, run KeysExistPass first
      *
      *  A circular reference means that b requires a and a requires b and impossible situation or
      *  a requires b and requires c but c requires a, a giant circle.
      *
      *  @param CompositeInterface $composite
      *  @param CompilerInterface $cmp
      */
    public function process(CompositeInterface $composite, CompilerInterface $cmp)
    {
        $graph = $cmp->getGraph();
        
        foreach ($graph->getNodes() as $id => $node) {
            $this->current_id = $id;
            $this->current_path = array($id);
            $this->checkOutEdges($node->getOutEdges());
        }
        
    }
    
    /**
     * Checks for circular references.
     *
     * @param array $edges An array of Nodes
     *
     * @throws CircularReferenceException When a circular reference is found.
     */
    protected function checkOutEdges(array $edges)
    {
        foreach ($edges as $edge) {
            $node = $edge->getDestNode();
            $this->current_path[] = $id = $node->getId();

            # if the top node (current_id) is found elseware in this path we have circular reference
            if ($this->current_id === $id) {
                throw new CircularReferenceException($this->current_id, $this->current_path);
            }
        
            if(count($node->getOutEdges()) > 0) {
              $this->checkOutEdges($node->getOutEdges());  
            }
            
            array_pop($this->current_path);
        }
    }
   
    
    
}
/* End of File */