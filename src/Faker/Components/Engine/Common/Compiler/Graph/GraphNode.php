<?php
namespace Faker\Components\Engine\Common\Compiler\Graph;

use Faker\Components\Engine\EngineException;

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
      *  @var boolean has the node been visited 
      */
    protected $visited;

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
        $this->visited    = false;
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
    
      /**
      *  Has this node been visited 
      */
    public function getVisited()
    {
        return $this->visited;
    }
    
    /**
      *  Set if this node has been visited
      *
      *  @access public
      *  @param boolean $visit
      */    
    public function setVisited($visit)
    {
        if(is_bool($visit) === false) {
            throw new EngineException('Visited argumemt must be a boolean was :: '.$visited);
        }
        
        $this->visited = $visit;
    }
}
/* End of Service */