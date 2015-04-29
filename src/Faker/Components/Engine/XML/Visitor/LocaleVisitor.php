<?php
namespace Faker\Components\Engine\XML\Visitor;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Locale\LocaleFactory;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\Composite\CompositeException;

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
        if($node instanceof TypeInterface) {
            
            # whats the locale
            $locale = $node->getOption('locale');
            
            if(empty($locale)) {
                throw new CompositeException($node,'Locale is empty');
            } else {
                # fetch flywight from factory
                $locale = $this->factory->create($locale);    
            }
            
            # assign to the composite
            $node->setLocale($locale);
        }
        
        
        return $node;
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
    
    public function visitDatasourceInjector(CompositeInterface $node)
    {
        return null;
    }

    //-------------------------------------------------------
    # Extras
    
    public function reset()
    {
        $this->factory = null;
    }
    
    public function getResult()
    {
        return null;    
    }
}

/* End of File */