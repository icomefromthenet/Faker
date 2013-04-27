<?php
namespace Faker\Components\Engine\Common\Compiler;

use Faker\Components\Engine\Common\Composite\CompositeInterface,
    Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph,
    Faker\Components\Engine\Common\Visitor\DirectedGraphVisitor;

/*
 * Compiler used to bind options into actions inside the Generater Composite.
 *
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
    protected $graphVisitor;
    
    /**
      *  Class Constructor  
      */
    public function __construct(DirectedGraphVisitor $visitor)
    {
        $this->graphVisitor = $visitor;
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
        $composite->acceptVisitor($this->graphVisitor);
        $this->setGraph($this->graphVisitor->getResult());
        
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