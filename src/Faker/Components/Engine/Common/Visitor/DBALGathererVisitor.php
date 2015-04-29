<?php
namespace Faker\Components\Engine\Common\Visitor;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\DBALTypeInterface;
use Faker\Components\Engine\Common\Formatter\ValueConverter;

/**
  *  This abstract visitor provides the interface for visitors to implement
  *  Each new visitor must subclass this to be compitable in the generator.
  *
  *  Each method must accept a composite node as only argument.
  *
  *  @since 1.0.4
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  */    
class DBALGathererVisitor extends BasicVisitor
{
    /**
      *  @var  Faker\Components\Engine\Common\Formatter\ValueConverter
      */
    protected $valueConverter;
    
    /**
      *  Class constructor
      *
      *  @access public
      *  @return void
      *  @param ValueConverter $converter
      */
    public function __construct(ValueConverter $converter = null)
    {
        if(!$converter instanceof ValueConverter) {
            $converter = new ValueConverter();
        }
        
        $this->valueConverter = $converter;
    }
    
    
    public function visitGeneratorInjector(CompositeInterface $node)
    {
        return null;   
    }
    
    public function visitLocaleInjector(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitDirectedGraphBuilder(CompositeInterface $composite)
    {
        return null;    
    }
    
    public function visitDatasourceInjector(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitDBALGatherer(CompositeInterface $node)
    {
        if($node instanceof DBALTypeInterface) {
             $this->valueConverter->set($node->getId(),$node->getDBALType());   
        }
        
    }
    
    
    
    //-----------------------------------------------------------
    # custom
    
    /**
      *  Fetch the bound valueConverter
      *
      *  @access public
      *  @return ValueConverter
      */
    public function getResult()
    {
        return $this->valueConverter;
    }
    
    /**
      *  Reset the visitor.
      *
      *  @access public
      *  @return void
      */
    public function reset()
    {
        unset($this->valueConverter);
        $this->valueConverter = new ValueConverter();
    }
    

}
/* End of File */