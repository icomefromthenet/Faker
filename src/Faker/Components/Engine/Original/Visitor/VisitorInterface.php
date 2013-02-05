<?php
namespace Faker\Components\Engine\Original\Visitor;

/*
 * interface VisitorInterface
 * Composite interfaces that accept visitor implement this interface
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
interface VisitorInterface 
{
    /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BaseVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BaseVisitor $visitor);        
    
}
/* End of File */