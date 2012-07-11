<?php
namespace Faker\Components\Faker\Compiler\Pass;

use Faker\Components\Faker\Compiler\CompilerPassInterface,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Visitor\GeneratorInjectorVisitor,
    Faker\Generator\GeneratorInterface,
    Faker\Generator\GeneratorFactory;
    

/*
 * class CacheInjectorPass
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class GeneratorInjectorPass implements CompilerPassInterface
{
    
    protected $factory;
    
    protected $default_generator;
    
    
    /**
      *  Class Constructor
      *
      *  @var Faker\Generator\GeneratorFactory
      */
    public function __construct(GeneratorFactory $factory, GeneratorInterface $default_generator)
    {
        $this->factory = $factory;
        $this->default_generator = $default_generator;
    }
    
    
    /**
      *  Will inject Cache into the composite
      *
      *  The References should be valid as
      *  missing ones will not cause error, run KeysExistPass first
      *
      *  @param CompositeInterface $composite
      */
    public function process(CompositeInterface $composite)
    {
        $visitor = new GeneratorInjectorVisitor($this->factory,$this->default_generator);
       
        $composite->acceptVisitor($visitor);
    }
    
    
}
/* End of File */