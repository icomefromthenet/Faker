<?php
namespace Faker\Components\Engine\XML\Compiler\Pass;

use Faker\Components\Engine\Common\Compiler\CompilerPassInterface,
    Faker\Components\Engine\Common\Compiler\CompilerInterface,
    Faker\Components\Engine\Common\Composite\CompositeInterface,
    Faker\Components\Engine\XML\Visitor\LocaleVisitor;

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