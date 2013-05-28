<?php
namespace Faker\Components\Engine\XML\Compiler\Pass;

use Faker\Components\Engine\Common\Compiler\CompilerPassInterface,
    Faker\Components\Engine\Common\Compiler\CompilerInterface,
    Faker\Components\Engine\Common\Composite\CompositeInterface,
    Faker\Components\Engine\XML\Visitor\GeneratorInjectorVisitor,
    PHPStats\Generator\GeneratorInterface,
    PHPStats\Generator\GeneratorFactory;
    

/*
 * class CacheInjectorPass
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class GeneratorInjectorPass implements CompilerPassInterface
{
    
    
    
    protected $visitor;
    
    /**
      *  Class Constructor
      *
      *  @var PHPStats\Generator\GeneratorFactory
      */
    public function __construct(GeneratorInjectorVisitor $visitor)
    {
        $this->visitor = $visitor;
    }
    
    
    /**
      *  Will inject Cache into the composite
      *
      *  The References should be valid as
      *  missing ones will not cause error, run KeysExistPass first
      *
      *  @param CompositeInterface $composite
      *  @param Compiler $cmp
      */
    public function process(CompositeInterface $composite,CompilerInterface $cmp)
    {
        $composite->acceptVisitor($this->visitor);
    }
    
    
}
/* End of File */