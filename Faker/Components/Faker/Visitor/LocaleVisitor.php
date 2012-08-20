<?php
namespace Faker\Components\Faker\Visitor;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Locale\LocaleFactory,
    Faker\Components\Faker\Type\Type,
    Faker\Components\Faker\Exception as FakerException;

/**
  *  This visitor will Inject locale objects into the composite.
  *
  *  @since 1.0.3
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  */    
class LocaleVisitor extends BaseVisitor
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
    public function visitLocale(CompositeInterface $composite)
    {
        if($composite instanceof Type) {
            
            # whats the locale
            $locale = $composite->getOption('locale');
            
            # fetch flywight from factory
            $locale = $this->factory->create($locale);
            
            # assign to the composite
            $composite->setLocale($locale);
        }
        
        
        return $composite;
    }
    
    
    public function visitGeneratorInjector(CompositeInterface $composite)
    {
         throw new FakerException('Not implemented');
    }
    
    public function visitCacheInjector(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitRefCheck(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitMapBuilder(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitDirectedGraph(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
}

/* End of File */