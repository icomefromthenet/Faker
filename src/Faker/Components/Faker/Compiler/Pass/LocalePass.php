<?php
namespace Faker\Components\Faker\Compiler\Pass;

use Faker\Components\Faker\Compiler\CompilerPassInterface,
    Faker\Components\Faker\Compiler\CompilerInterface,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Visitor\LocaleVisitor;

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