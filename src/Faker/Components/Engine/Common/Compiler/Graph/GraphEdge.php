<?php
namespace Faker\Components\Engine\Common\Compiler\Graph;


/**
* Represents an edge in your service graph.
*
* @author Lewis Dyer <getintouch@icomefromthenet.com>
* @since 1.0.3
*/
class GraphEdge
{
    /**
      *  @var GraphNode 
      */
    protected $sourceNode;
    
    /**
      * @var GraphNode  
      */
    protected $destNode;
    
    /**
      *  @var string  
      */
    protected $value;

   
    
    /**
    * Constructor.
    *
    * @param GraphNode $sourceNode
    * @param GraphNode $destNode
    * @param string $value
    */
    public function __construct(GraphNode $sourceNode, GraphNode $destNode, $value = null)
    {
        $this->sourceNode = $sourceNode;
        $this->destNode   = $destNode;
        $this->value      = $value;
    }

    /**
    * Returns the value of the edge
    *
    * @return GraphNode
    * @access public
    */
    public function getValue()
    {
        return $this->value;
    }

    /**
    * Returns the source node
    *
    * @return GraphNode
    * @access public
    */
    public function getSourceNode()
    {
        return $this->sourceNode;
    }

    /**
    * Returns the destination node
    *
    * @return GraphNode
    * @access public
    */
    public function getDestNode()
    {
        return $this->destNode;
    }
     
}
/* End of File */