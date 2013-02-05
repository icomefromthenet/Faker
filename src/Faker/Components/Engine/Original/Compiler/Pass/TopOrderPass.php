<?php
namespace Faker\Components\Engine\Original\Compiler\Pass;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Composite\Table,
    Faker\Components\Engine\Original\Compiler\Graph\GraphNode,
    Faker\Components\Engine\Original\Compiler\CompilerPassInterface,
    Faker\Components\Engine\Original\Compiler\CompilerInterface,
    Faker\Components\Engine\Original\Composite\CompositeInterface;

/*
 *  Topological Reorder for DirectGraph.
 *
 *
 *  Use to re-order a composite with forign-keys
 */
class TopOrderPass implements CompilerPassInterface
{
    
    /**
      *  @var array the new order of the composite 
      */
    protected $new_order;
    
    /**
      *  @var array a list of nodes with no out edges 
      */
    protected $nodes_no_out_edges;
    
    /**
      *  Class Constructor 
      */
    public function __construct()
    {
        $this->new_order = array();
        $this->nodes_no_out_edges = array();
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
            if($node->getValue() instanceof Table) {
                
                $tmp_nodes = $node->getOutEdges();
                $table_node_count = 0;
                
                # count how many outer edges are just tables
                foreach($tmp_nodes as $tmpnode) {
                    if($tmpnode->getDestNode()->getValue() instanceof Table) {
                        $table_node_count += 1;
                    }
                }
                
                # test if no tables found in outer edges
                if($table_node_count === 0) {
                    $this->nodes_no_out_edges[] = $node;
                }
                
                $tmp_nodes = null;
            }
        }
        
        # Call breath first search on each table node with no outer-edges
        # As we have a directed acyclic graph there is guarantee of minimum 1 node without outer-edges.
        foreach($this->nodes_no_out_edges as $node) {
                $this->visitNode($node);
        }
        
        $this->sortComposite($composite,$this->new_order);
    
        # clear for another possible pass
        $this->clear();
    }
    
    
    public function visitNode(GraphNode $node)
    {
        if($node->getValue() instanceof Table && $node->getVisited() === false) {
            $node->setVisited(true);
            
            # follow the nodes inEdges to find its ansestors.
            foreach($node->getInEdges() as $childnode) {
                if($childnode->getSourceNode()->getValue() instanceof Table) {
                    $this->visitNode($childnode->getSourceNode());    
                }
            }
            array_unshift($this->new_order,$node);
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
        $composite->removeChildren();
        
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
        $this->new_order          = array();
        $this->nodes_no_out_edges = array();
    }
    
}
/* End of File */