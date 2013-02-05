<?php
namespace Faker\Components\Engine\Original\Visitor;

use Faker\Components\Engine\Original\Composite\CompositeInterface;

/*
 * class BaseVisitor
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
abstract class BaseVisitor
{
    
    //------------------------------------------------------------------
    # Visitor Methods
    
    abstract public function visitCacheInjector(CompositeInterface $composite);
    
    abstract public function visitRefCheck(CompositeInterface $composite);
    
    abstract public function visitMapBuilder(CompositeInterface $composite);
    
    abstract public function visitGeneratorInjector(CompositeInterface $composite);
    
    abstract public function visitLocale(CompositeInterface $composite);
    
    abstract public function visitDirectedGraph(CompositeInterface $composite);
    
    //------------------------------------------------------------------
}
/* End of File */