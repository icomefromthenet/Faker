<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;
use Faker\Components\Engine\Common\Builder\AbstractDefinition;
use Faker\Components\Engine\Common\Composite\SelectorNode;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Selector\AlternateSelector;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

/**
  *  Allows the Alternate Selector to be created and populated with types
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorAlternateBuilder extends NodeCollection implements TypeDefinitionInterface
{
    
    
    protected $attributes = array();
    
    
    //------------------------------------------------------------------
    #TypeDefinitionInterface
    
    public function locale(LocaleInterface $locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    
    public function generator(GeneratorInterface $gen)
    {
        $this->generator = $gen;
        
        return $this;
    }
    
    
    public function utilities(Utilities $util)
    {
        $this->utilities = $utilities;
        
        return $this;
    }
    
    
    public function eventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        
        return $this;
    }
    
    
    public function database(Connection $conn)
    {
        $this->database = $conn;
        
        return $this;
    }
    
    public function templateLoader(Loader $template)
    {
        $this->templateLoader = $template;
    }
        
    public function attribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }
    
    
    //------------------------------------------------------------------
    
    /**
      *  Allows the description of the selector
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\NodeBuilder
      */
    public function describe()
    {
        # create new node builder
        $nodeBuilder = new NodeBuilder('alternateSelectorBuilder',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        
        # bind this definition as the parent of nodebuilder
        $nodeBuilder->setParent($this);
        
        # return node builder to continue chain
        return $nodeBuilder;
    }
    
    //------------------------------------------------------------------
    # NodeCollection
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        # construct the selector type
        $type = new AlternateSelector();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        $type->setOption('set',count($this->children()));
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        # return the composite generator selectorNode
        return new SelectorNode('selectorNode',$this->eventDispatcher,$type); 
    }
    
    
     /**
    * Returns the parent node.
    *
    * @return ParentNodeInterface The builder of the parent node
    */
    public function end()
    {
        # construct the node from this definition.
        $node     = $this->getNode();
        $parent   = $this->getParent();
        $children = $this->children();

        foreach($children as $child) {
            $node->addChild($child);
        }    
        
        # append generators compositeNode to the parent builder.
        $parent->append($node);
        
        # return the parent to continue chain.
        return $parent;
    }
    
   /**
    *  The number of passes to make before alternating
    *
    *  @param integer $value the step size
    *  @return SelectorAlternateBuilder
    *  @access public
    */
   public function step($value)
   {
        $this->attribute('step',$value);
        
        return $this;
   }
   
   
  
}
/* End of file */
