<?php
namespace Faker\Components\Engine\XML\Visitor;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Locale\LocaleFactory;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;

/**
  *  This visitor will Inject locale objects into the composite.
  *
  *  @since 1.0.3
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  */    
class LocaleVisitor extends BasicVisitor
{
    
    /**
      *  @var Faker\Locale\LocaleFactory 
      */
    protected $factory;
    
    
    /**
      *  Class Constructor
      *
      *  @param LocaleFactory $factory
      *  @access public
      */
    public function __construct(LocaleFactory $factory)
    {
        $this->factory = $factory;
    }
    
    /**
      *  Will inject a locale into composite, which locale is
      *  decided by the composite locale option.
      *
      *  @access public
      *  @para CompositeInterface $composite
      */
    public function visitLocaleInjector(CompositeInterface $node)
    {
        if($composite instanceof TypeInterface) {
            
            # whats the locale
            $locale = $composite->getOption('locale');
            
            # fetch flywight from factory
            $locale = $this->factory->create($locale);
            
            # assign to the composite
            $composite->setLocale($locale);
        }
        
        
        return $composite;
    }
    
    
  
    public function visitGeneratorInjector(CompositeInterface $node)
    {
        
    }
    
    public function visitDBALGatherer(CompositeInterface $node)
    {
        
    }
    
    public function visitDirectedGraphBuilder(CompositeInterface $node)
    {
        
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