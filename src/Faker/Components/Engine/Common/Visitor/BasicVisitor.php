<?php
namespace Faker\Components\Engine\Common\Visitor;

use Faker\Components\Engine\Common\Composite\CompositeInterface;

/**
  *  This abstract visitor provides the interface for visitors to implement
  *  Each new visitor must subclass this to be compitable in the generator.
  *
  *  Each method must accept a composite node as only argument.
  *
  *  @since 1.0.4
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  */    
abstract class BasicVisitor
{
    
    /**
     *  Inject Random Number Generator
     *
     *  @access public
     *  @param CompositeInterface $node
     *
    */
    abstract public function visitGeneratorInjector(CompositeInterface $node);
    
    /**
      *  Will inject a locale into composite, which locale is
      *  decided by the composite locale option.
      *
      *  @access public
      *  @param CompositeInterface $node
      */
    abstract public function visitLocaleInjector(CompositeInterface $node);
    
    /**
      *  Will Gather the dbal column types if the node implements the DBALTypeInterface
      *
      *  @access public
      *  @param CompositeInterface $node
      */
    abstract public function visitDBALGatherer(CompositeInterface $node);
    
    /**
      *  Will build a directed graph from a composite
      *
      *  @access public
      *  @param CompositeInterface $node
      */
    abstract public function visitDirectedGraphBuilder(CompositeInterface $node);
    
    
    /**
      *  Reset a visitor for another run
      *
      *  @access public
      *  @return void
      */
    abstract public function reset();
    
    /**
      *  Where visitors are used to gather a result this will
      *  return it
      *
      *  @return mixed
      */
    abstract public function getResult();

}
/* End of File */