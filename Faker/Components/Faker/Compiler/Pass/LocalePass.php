<?php
namespace Faker\Components\Faker\Compiler\Pass;

use Faker\Components\Faker\Compiler\CompilerPassInterface,
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
      */
    public function process(CompositeInterface $composite)
    {
        $composite->acceptVisitor($this->visitor);
    }
    
    
}
/* End of File */