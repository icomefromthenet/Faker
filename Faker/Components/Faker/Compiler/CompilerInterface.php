<?php
namespace Faker\Components\Faker\Compiler;

use Faker\Components\Faker\Composite\CompositeInterface;

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
    
}
/* End of File */