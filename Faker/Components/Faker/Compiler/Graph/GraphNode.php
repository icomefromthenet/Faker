<?php
namespace Faker\Components\Faker\Compiler\Graph;

use Faker\Components\Faker\Exception as FakerException;

/**
* Represents a node in your service graph.
*
* @author Lewis Dyer <getintouch@icomefromthenet.com>
* @since 1.0.3
*/
class GraphNode
{
    /**
      *  @var string the id 
      */
    protected $id;
    
    /**
      *  @var array GraphEdge[] 
      */
    protected $inEdges;
    
    /**
      *  @var array GraphEdge[] 
      */
    protected $outEdges;
    
    /**
      *  @var string the value 
      */
    protected $value;

    /**
    * Constructor.
    *
    * @param string $id The node identifier
    * @param mixed $value The node value
    */
    public function __construct($id, $value)
    {
        $this->id = $id;
        $this->value = $value;
        $this->inEdges = array();
        $this->outEdges = array();
    }

    /**
    * Adds an in edge to this node.
    *
    * @param GraphEdge $edge
    * @access public
    */
    public function addInEdge(GraphEdge $edge)
    {
        $this->inEdges[] = $edge;
    }

    /**
    * Adds an out edge to this node.
    *
    * @param GraphEdge $edge
    * @access public
    */
    public function addOutEdge(GraphEdge $edge)
    {
        $this->outEdges[] = $edge;
    }

    /**
    * Returns the identifier.
    *
    * @return string
    * @access public
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Returns the in edges.
    *
    * @return array The in GraphEdge array
    * @access public
    */
    public function getInEdges()
    {
        return $this->inEdges;
    }

    /**
    * Returns the out edges.
    *
    * @return array The out GraphEdge array
    * @access public
    */
    public function getOutEdges()
    {
        return $this->outEdges;
    }

    /**
    * Returns the value of this Node
    *
    * @return mixed The value
    * @access public
    */
    public function getValue()
    {
        return $this->value;
    }
}
/* End of Service */