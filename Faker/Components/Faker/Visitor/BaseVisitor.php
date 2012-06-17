<?php
namespace Faker\Components\Faker\Visitor;

use Faker\Components\Faker\Composite\CompositeInterface;

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
    
    
    //------------------------------------------------------------------
}
/* End of File */