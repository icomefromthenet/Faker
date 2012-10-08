<?php
namespace Faker\Components\Faker\Compiler\Graph;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Composite\Schema;

/**
* This is a directed graph of your services.
*
* @author Lewis Dyer <getintouch@icomefromthenet.com>
* @since 1.0.3
*/
class DirectedGraph
{

    /**
      *  @var Faker\Components\Faker\BaseNode 
      */
    protected $nodes;

    /**
      *  @var Faker\Components\Faker\Composite\Schema
      */
    protected $schema;

    /**
      *  Class Constructor 
      */
    public function __construct()
    {
        $this->nodes = array();
    }


    /**
    * Checks if the graph has a specific node.
    *
    * @param string $id Id to check
    */
    public function hasNode($id)
    {
        return isset($this->nodes[$id]);
    }

    /**
    * Gets a node by identifier.
    *
    * @param string $id The id to retrieve
    *
    * @return ServiceReferenceGraphNode The node matching the supplied identifier
    *
    * @throws InvalidArgumentException if no node matches the supplied identifier
    */
    public function getNode($id)
    {
        if (!isset($this->nodes[$id])) {
            throw new FakerException(sprintf('There is no node with id "%s".', $id));
        }

        return $this->nodes[$id];
    }

    /**
    * Returns all nodes.
    *
    * @return array An array of all ServiceReferenceGraphNode objects
    * @access public
    */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
    * Clears all nodes.
    *
    * @access public
    * @return DirectedGraph
    */
    public function clear()
    {
        $this->nodes  = array();
        $this->schema = null;
        
        return $this;
    }

    /**
    * Connects 2 nodes together in the Graph.
    *
    * @param string $source_id
    * @param string $source_value
    * @param string $dest_id
    * @param string $dest_value
    * @param string $reference
    * @access public
    * @return DirectedGraph
    */
    public function connect($source_id, $source_value, $dest_id, $dest_value = null, $reference = null)
    {
        $source_node = $this->createNode($source_id, $source_value);
        $dest_node = $this->createNode($dest_id, $dest_value);
        
        $edge = new GraphEdge($source_node, $dest_node, $reference);

        $source_node->addOutEdge($edge);
        $dest_node->addInEdge($edge);
        
        return $this;
    }

    /**
    * Creates a graph node.
    *
    * @param string $id
    * @param string $value
    * @access protected
    * @return GraphNode
    */
    protected function createNode($id, $value)
    {
        if (isset($this->nodes[$id]) && $this->nodes[$id]->getValue() === $value) {
            return $this->nodes[$id];
        }

        return $this->nodes[$id] = new GraphNode($id, $value);
    }
    
    /**
      *  Sets the schema (root node)
      *
      *  @param Schema $schema
      *  @return void
      *  @access public
      */
    public function setSchema(Schema $schema)
    {
        $this->schema = $schema;
    }
    
    /**
      *  Fetches the schema (root)
      *
      *  @return Schema
      *  @access public
      */
    public function getSchema()
    {
        return $this->schema;
    }
    
}
/* End of Service */