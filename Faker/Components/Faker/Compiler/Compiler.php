<?php
namespace Faker\Components\Faker\Compiler;

use Faker\Components\Faker\Composite\CompositeInterface;

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
        foreach($this->passes as $pass) {
            $pass->process($composite);    
        }
        
        return $composite;
    }
    
}
/* End of File */