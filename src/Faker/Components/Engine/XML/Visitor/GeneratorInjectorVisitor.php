<?php
namespace Faker\Components\Engine\XML\Visitor;

use PHPStats\Generator\GeneratorFactory;
use PHPStats\Generator\GeneratorInterface;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\XML\Composite\TypeNode;
use Faker\Components\Engine\XML\Composite\TableNode;
use Faker\Components\Engine\XML\Composite\ColumnNode;
use Faker\Components\Engine\XML\Composite\SchemaNode;
use Faker\Components\Engine\XML\Composite\SelectorNode;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;


/*
 * ColumnCacheInjectorVisitor
 *
 * Will inject a cache into a column or fk
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class GeneratorInjectorVisitor extends BasicVisitor
{
    
    /**
      *  @var  PHPStats\Generator\GeneratorInterface a generator defined in schema declaration
      */
    protected $headGenerator;
    
    /**
      *  @var PHPStats\Generator\GeneratorFactory the generator factory 
      */
    protected $factory;
    
   
    public function __construct(GeneratorFactory $factory, GeneratorInterface $default)
    {
        $this->headGenerator = $default;
        $this->factory          = $factory;
    }
    
    
    //------------------------------------------------------------------
    # Visitor Methods
    
    
    public function visitGeneratorInjector(CompositeInterface $composite)
    {
         $seed = null;
         
         if($composite instanceof SchemaNode) {
            
            # use the schema setting or keep the default global
            if($composite->hasOption('randomGenerator') === true) {
                
                if($composite->hasOption('generatorSeed') === true) {
                    $seed = $composite->getOption('generatorSeed');
                }
                # re-assign the global
                $this->headGenerator = $this->factory->create($composite->getOption('randomGenerator'),$seed);       
                
                
            } 
            
            # assign schema the default generator or the custom just setup above
            $composite->setGenerator($this->global_generator);
        }
        else {
             # use the schema setting or keep the default global
            if($composite->hasOption('randomGenerator') === true) {
                
                if($composite->hasOption('generatorSeed') === true) {
                    $seed = $composite->getOption('generatorSeed');
                }
                # re-assign the global
                
                $composite->setGenerator($this->global_generator);
                
            }
        
        }
    }
    
  
    public function visitLocaleInjector(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitDBALGatherer(CompositeInterface $node)
    {
        return null;    
    }
    
    public function visitDirectedGraphBuilder(CompositeInterface $node)
    {
        return null;
    }
    
    //-------------------------------------------------------
    # Extras
    
    public function reset()
    {
        $this->table_generator = null;
        $this->column_generator = null;
    }
    
    public function getResult()
    {
        return null;    
    }

}
/* End of File */