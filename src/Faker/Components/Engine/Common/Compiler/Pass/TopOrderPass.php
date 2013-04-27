<?php
namespace Faker\Components\Engine\Common\Compiler\Pass;

use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Composite\FormatterNode;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Compiler\Graph\GraphNode;
use Faker\Components\Engine\Common\Compiler\CompilerPassInterface;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;

/*
 *  Topological Reorder for DirectGraph.
 *
 *  Use to re-order a composite with forign-keys
 *  @since 1.0.2
 */
class TopOrderPass implements CompilerPassInterface
{
    
    /**
      *  @var array the new order of the composite 
      */
    protected $newOrder;
    
    /**
      *  @var array a list of nodes with no out edges 
      */
    protected $nodesNoOutEdges;
    
    /**
      *  Class Constructor 
      */
    public function __construct()
    {
        $this->newOrder = array();
        $this->nodesNoOutEdges = array();
    }
    
    
    /**
      *  Change the order of the tables to use Topological Order
      *
      *  @access public
      *  @param CompositeInterface $compiler
      *  @param CompilerInterface  $cmp
      *  @link using breath search algorithm first http://en.wikipedia.org/wiki/Topological_sorting
      */
    public function process(CompositeInterface $composite, CompilerInterface $cmp)
    {
        $graph = $cmp->getGraph();
        
        # Find table nodes with no out-edges.
        foreach($graph->getNodes() as $node) {
            if($node->getValue() instanceof TableNode || $node->getValue() instanceof FormatterNode) {
                
                $tmp_nodes = $node->getOutEdges();
                $table_node_count = 0;
                
                # count how many outer edges are just tables
                foreach($tmp_nodes as $tmpnode) {
                    if($tmpnode->getDestNode()->getValue() instanceof TableNode || $tmpnode->getDestNode()->getValue() instanceof FormatterNode) {
                        $table_node_count += 1;
                    }
                }
                
                # test if no tables found in outer edges
                if($table_node_count === 0) {
                    $this->nodesNoOutEdges[] = $node;
                }
                
                $tmp_nodes = null;
            }
        }
        
        # Call breath first search on each table node with no outer-edges
        # As we have a directed acyclic graph there is guarantee of minimum 1 node without outer-edges.
        foreach($this->nodesNoOutEdges as $node) {
                $this->visitNode($node);
        }
        
        $this->sortComposite($composite,$this->newOrder);
    
        # clear for another possible pass
        $this->clear();
    }
    
    
    public function visitNode(GraphNode $node)
    {
        if(($node->getValue() instanceof TableNode || $node->getValue() instanceof FormatterNode)  && $node->getVisited() === false) {
            $node->setVisited(true);
            
            # follow the nodes inEdges to find its ansestors.
            foreach($node->getInEdges() as $childnode) {
                $sourceNode = $childnode->getSourceNode();
                $sourceNodeValue = $sourceNode ->getValue();
                
                if($sourceNodeValue instanceof TableNode || $sourceNodeValue instanceof FormatterNode ) {
                    $this->visitNode($sourceNode);    
                }
                
                $sourceNode = null;
            }
            array_unshift($this->newOrder,$node);
        } 
        
    }
    
    /**
      *  Set the composite Schema with new Table order
      *
      *  @access public
      *  @param CompositeInterface $composite
      *  @param array $order the node list
      */    
    public function sortComposite(CompositeInterface $composite,array $order)
    {
        $composite->clearChildren();
        
        foreach ($order as $node) {
            $composite->addChild($node->getValue());
        }
        
    }
    
    /**
      *  Clears internal state ready for another run.
      *
      *  @access public
      *  @return void
      */
    public function clear()
    {
        $this->newOrder          = array();
        $this->nodesNoOutEdges = array();
    }
    
}
/* End of File */