<?php
namespace Faker\Components\Engine\Original\Compiler;

use Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Compiler\Graph\DirectedGraph;

/*
 * interface CompilerInterface
 *
 * Used to check foreign key references and inject caches
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */

interface CompilerInterface 
{
    
    /**
      *  Add a pass to the compiler
      *
      *  @access public
      *  @param CompilerPassInterface $pass
      */
    public function addPass(CompilerPassInterface $pass);
    
    /**
      *  Compile a Composite
      *
      *  @access public
      *  @param CompositeInterface $composite
      */
    public function compile(CompositeInterface $composite);
    
    /**
      *  Set the Directed Graph so only run once
      *
      *  @access public
      *  @param DirectedGraph $graph
      *  @return void
      */
    public function setGraph(DirectedGraph $graph);
    
    /**
      *  Fetch the DirectedGraph
      *
      *  @access public
      *  @return DirectedGraph
      */
    public function getGraph();
    
}
/* End of File */