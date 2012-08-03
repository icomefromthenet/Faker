<?php
namespace Faker\Components\Faker\Compiler;

use Faker\Components\Faker\Composite\CompositeInterface;


/*
 * interface CompilerPassInterface
 *
 * A compiler pass must implement this interface.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
interface CompilerPassInterface
{
    /**
      *  Will be called as part of a compiler.
      *
      *  @access public
      *  @param CompositeInterface $compiler
      *  @param CompilerInterface  $cmp
      */
    public function process(CompositeInterface $composite, CompilerInterface $cmp);    
    
}
/* End of File */