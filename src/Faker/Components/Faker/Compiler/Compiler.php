<?php
namespace Faker\Components\Faker\Compiler;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Compiler\Graph\DirectedGraph,
    Faker\Components\Faker\Visitor\DirectedGraphVisitor;

/*
 * class Compiler
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class Compiler implements CompilerInterface
{
    /**
      *  @var CompilerPassInterface[] the passes to run. 
      */
    protected $passes;
    
    /**
      *  @var DirectedGraph 
      */
    protected $graph;
    
    /**
      *  @var DirectedGraphVisitor 
      */
    protected $graph_visitor;
    
    /**
      *  Class Constructor  
      */
    public function __construct(DirectedGraphVisitor $visitor)
    {
        $this->graph_visitor = $visitor;
    }
    
    
    /**
      *  Add a pass to the compiler
      *
      *  @access public
      *  @param CompilerPassInterface $pass
      */
    public function addPass(CompilerPassInterface $pass)
    {
        $this->passes[] = $pass;
    }
    
    /**
      *  Compile a Composite
      *
      *  @access public
      *  @param CompositeInterface $composite
      */
    public function compile(CompositeInterface $composite)
    {
        $composite->acceptVisitor($this->graph_visitor);
        $this->setGraph($this->graph_visitor->getDirectedGraph());
        
        foreach($this->passes as $pass) {
            $pass->process($composite,$this);    
        }
        
        return $composite;
    }
    
    /**
      *  Set the Directed Graph so only run once
      *
      *  @access public
      *  @param DirectedGraph $graph
      *  @return void
      */
    public function setGraph(DirectedGraph $graph)
    {
        $this->graph = $graph;
    }
    
    /**
      *  Fetch the DirectedGraph
      *
      *  @access public
      *  @return DirectedGraph
      */
    public function getGraph()
    {
        return $this->graph;
    }
    
}
/* End of File */