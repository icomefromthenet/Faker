<?php
namespace Faker\Components\Engine\Original\Compiler\Pass;

use Faker\Components\Engine\Original\Compiler\CompilerPassInterface,
    Faker\Components\Engine\Original\Compiler\CompilerInterface,
    Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Visitor\LocaleVisitor;

/*
 * class LocalePass
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.3
 */
class LocalePass implements CompilerPassInterface
{
        
    protected $visitor;
    
    /**
      *  Class Constructor
      *
      *  @param LocaleVisitor $visitor
      */
    public function __construct(LocaleVisitor $visitor)
    {
        $this->visitor = $visitor;
        
    }
    
    
    /**
      *  Will inject Locale into the composite
      *
      *  @param CompositeInterface $composite
      *  @param CompilerInterface $cmp
      */
    public function process(CompositeInterface $composite,CompilerInterface $cmp)
    {
        $composite->acceptVisitor($this->visitor);
    }
    
    
}
/* End of File */