<?php
namespace Faker\Components\Faker\Visitor;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Composite\Table,
    Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\Schema,
    Faker\Components\Faker\Composite\Random,
    Faker\Components\Faker\Composite\Pick,
    Faker\Components\Faker\Type\Type,
    Faker\Generator\GeneratorFactory,
    Faker\Generator\GeneratorInterface,
    Faker\Components\Faker\Exception as FakerException;

/*
 * class ColumnCacheInjectorVisitor
 *
 * Will inject a cache into a column or fk
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class GeneratorInjectorVisitor extends BaseVisitor
{
    
    /**
      *  @var  Faker\Generator\GeneratorInterface a generator defined in schema declaration
      */
    protected $global_generator;
    
    /**
      *  @var  Faker\Generator\GeneratorInterface a generator defined in last table declaration
      */        
    protected $table_generator;
    
    /**
      *  @var  Faker\Generator\GeneratorInterface a generator defined in last column declaration
      */
    protected $column_generator;

    /**
      *  @var Faker\Generator\GeneratorFactory the generator factory 
      */
    protected $factory;
    
   
    public function __construct(GeneratorFactory $factory, GeneratorInterface $default)
    {
        $this->global_generator = $default;
        $this->factory          = $factory;
    }
    
    
    //------------------------------------------------------------------
    # Visitor Methods
    
    
    public function visitGeneratorInjector(CompositeInterface $composite)
    {
         $seed = null;
         
         if($composite instanceof Schema) {
            
            # use the schema setting or keep the default global
            if($composite->hasOption('randomGenerator') === true) {
                
                if($composite->hasOption('generatorSeed') === true) {
                    $seed = $composite->getOption('generatorSeed');
                }
                # re-assign the global
                $this->global_generator = $this->factory->create($composite->getOption('randomGenerator'),$seed);       
                
            }
        }
        
        if($composite instanceof Table) {
            # reset the column and table generators
            $this->column_generator = null;
            $this->table_generator = null;
            
            # use the table setting
            if($composite->hasOption('randomGenerator') === true) {
                
                if($composite->hasOption('generatorSeed') === true) {
                    $seed = $composite->getOption('generatorSeed');
                }
                
                $this->table_generator = $this->factory->create($composite->getOption('randomGenerator'),$seed);  
                
            } else {
                # use the schema setting
                $this->table_generator = $this->global_generator;                
            }
            
        }
        
        if($composite instanceof Column) {
            # reset the column 
            $this->column_generator = null;
            
            # use the column setting
            if($composite->hasOption('randomGenerator') === true) {
                
                if($composite->hasOption('generatorSeed') === true) {
                    $seed = $composite->getOption('generatorSeed');
                }
                
                $this->column_generator = $this->factory->create($composite->getOption('randomGenerator'),$seed);  
                
            } else {
                 # use the table setting
                $this->column_generator = $this->table_generator;                
            }
           
            
        }
        
        if($composite instanceof Random) {
            # use the internal setting
            $composite->setGenerator($this->column_generator);
        }
        
        if($composite instanceof Pick) {
            # use the internal setting
            $composite->setGenerator($this->column_generator);
        }
        
        
        if($composite instanceof Type) {
            
            # use the internal setting
            if($composite->hasOption('randomGenerator') === true) {
                
                if($composite->hasOption('generatorSeed') === true) {
                    $seed = $composite->getOption('generatorSeed');
                }
                
                $composite->setGenerator($this->factory->create($composite->getOption('randomGenerator'),$seed));  
                
            } 
             else { 
                # inject column value
                $composite->setGenerator($this->column_generator);
            }
             
        }
        
        
    }
    
  
    public function visitCacheInjector(CompositeInterface $composite)
    {
       throw new FakerException('Not implemented');
    }
    
    public function visitRefCheck(CompositeInterface $composite)
    {
        throw new FakerException('Not implemented');
    }
    
    public function visitMapBuilder(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitLocale(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }

}
/* End of File */