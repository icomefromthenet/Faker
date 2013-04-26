<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\NodeCollection;
use Faker\Components\Engine\Common\Builder\SelectorListInterface;
use Faker\Components\Engine\Common\Builder\FieldListInterface;
use Faker\Components\Engine\Entity\Composite\FieldNode;
use Faker\Components\Engine\Entity\Builder\Selector\SelectorAlternateBuilder;
use Faker\Components\Engine\Entity\Builder\Selector\SelectorRandomBuilder;
use Faker\Components\Engine\Entity\Builder\Selector\SelectorSwapBuilder;
use Faker\Components\Engine\Entity\Builder\Selector\SelectorWeightBuilder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

/**
  *  Builder to construct FieldDefinitions and SelectorDefinitions
  *
  *  This implements SelectorListInterface and FieldListInterface
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class NodeBuilder extends NodeCollection implements SelectorListInterface, FieldListInterface
{
    
    /**
      *  Create the field node that hold the child selectors and types.
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        return new null;
    }
   
    /**
    *  Send the child compositeNodes to parent builder
    *
    *  @return FieldBuilder The parent builder 
    *  @access public
    */
    public function end()
    {
        $children = $this->children();
        $parent   = $this->getParent();
        
        #append children to parent builder
        foreach($children as $child) {
            $parent->append($child);
        }
        
        # return parent to continue chain.
        return $parent;
    }
    
    
    
    /**
      *  Find the Type from the TypeDefinitionRepo and return an instanced definition
      *
      *  @access protected
      *  @return Faker\Components\Engine\Entity\Builder\Type\TypeDefinitionInterface
      */    
    protected function create($alias)
    {
        $field = new $alias();
        
        # set the basic fields need by each type
        $field->generator($this->generator);
        $field->utilities($this->utilities);
        $field->database($this->database);
        $field->locale($this->locale);
        $field->eventDispatcher($this->eventDispatcher);
        $field->templateLoader($this->templateLoader);
        $field->setParent($this);
        
        # return the definition for configuration by user
        return $field;
    }
    
    /**
      *  Return an Alphnumeric field for configuration
      *  
      *  @return Faker\Components\Engine\Entity\Builder\Type\AlphaNumericTypeDefinition
      *  @access public   
      */
    public function fieldAlphaNumeric()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\AlphaNumericTypeDefinition');
    }
    
    /**
      *  Return an  field for configuration
      *  
      *  @return Faker\Components\Engine\Entity\Builder\Type\AutoIncrementTypeDefinition
      *  @access public   
      */
    public function fieldAutoIncrement()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\AutoIncrementTypeDefinition');
    }
    
    /**
      * Return an boolean field for configuration
      * 
      *  @return Faker\Components\Engine\Entity\Builder\Type\BooleanTypeDefinition
      *  @access public   
      */
    public function fieldBoolean()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\BooleanTypeDefinition');
    }
    
    /**
      *  Return an City field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\CitiesTypeDefinition
      *  @access public
      */
    public function fieldCity()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\CitiesTypeDefinition');
    }
    
    /**
      *  Return an Constant field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\ConstantNumberTypeDefinition 
      *  @access public   
      */
    public function fieldConstant()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\ConstantNumberTypeDefinition');
    }
    
    /**
      *  Return an Country field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\CountryTypeDefinition
      *  @access public
      */
    public function fieldCountry()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\CountryTypeDefinition');
    }
    
    /**
      * Return an Date field for configuration
      * 
      *  @return Faker\Components\Engine\Entity\Builder\Type\DateTypeDefinition
      *  @access public
      */
    public function fieldDate()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\DateTypeDefinition');
    }
    
    /**
      * Return an Email field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\EmailTypeDefinition
      *  @access public
      */
    public function fieldEmail()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\EmailTypeDefinition');
    }
    
    /**
      * Return a People field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\NamesTypeDefinition
      *  @access public
      */
    public function fieldPeople()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\NamesTypeDefinition');
    }
    
    /**
      * Return a Null field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\NullTypeDefinition
      *  @access public
      */
    public function fieldNull()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\NullTypeDefinition');
    }
    
    /**
      * Return a Numeric field for configuration
      *  
      *  @return Faker\Components\Engine\Entity\Builder\Type\NumericTypeDefinition
      *  @access public
      */
    public function fieldNumeric()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\NumericTypeDefinition');
    }
    
    /**
     *  Return an Range Definition
     *
     *  @return Faker\Components\Engine\Entity\Builder\Type\RangeTypeDefinition
     *  @access public   
     */
    public function fieldRange()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\RangeTypeDefinition');
    }
    
    /**
      *  Return a Regex field for configuration
      * 
      *  @return Faker\Components\Engine\Entity\Builder\Type\RegexTypeDefinition
      *  @access public   
      */
    public function fieldRegex()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\RegexTypeDefinition');
    }
    
    /**
      * Return a Template field for configuration
      * 
      * @return Faker\Components\Engine\Entity\Builder\Type\TemplateTypeDefinition
      * @access public
      */
    public function fieldTemplate()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\TemplateTypeDefinition');
    }
    
    /**
      * Return a Text field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\TextTypeDefinition
      *  @access public   
      */
    public function fieldText()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\TextTypeDefinition');
    }
    
    /**
      *  Return a Unique Number  field for configuration
      *
      *  @return Faker\Components\Engine\Entity\Builder\Type\UniqueNumberTypeDefinition
      *  @access public   
      */
    public function fieldUniqueNumber()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\UniqueNumberTypeDefinition');
    }
    
    /**
      *  Return a Unique String field for configuration
      *  
      *  @return Faker\Components\Engine\Entity\Builder\Type\UniqueStringTypeDefinition
      *  @access public
      */
    public function fieldUniqueString()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\UniqueStringTypeDefinition');
    }
    
    /**
      *  Return a closure type for configuration
      *
      *  @access public
      *  @return Faker\Components\Engine\Entity\Builder\Type\ClosureTypeDefinition
      */
    public function fieldClosure()
    {
        return $this->create('\Faker\Components\Engine\Entity\Builder\Type\ClosureTypeDefinition');
    }
    
     /**
      *  Return a alternate selector builder that alternatve of values
      *
      *  @access public
      *  @return Faker\Components\Engine\Entity\Builder\Selector\SelectorAlternateBuilder
      */ 
    public function selectorAlternate()
    {
        $node = new SelectorAlternateBuilder('SelectorAlternate',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
                
        return $node;
    }
    
    /**
      *  Return a builder that picks a type at random from the supplied list
      *
      *  @access public
      *  @return Faker\Components\Engine\Entity\Builder\Selector\SelectorRandomBuilder
      */
    public function selectorRandom()
    {
        $node = new SelectorRandomBuilder('SelectorRandom',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Return a builder that allows alternation that preferences the left or right value.
      *
      *  @access public
      *  @return Faker\Components\Engine\Entity\Builder\Selector\SelectorWeightBuilder
      */
    public function selectorWeightAlternate()
    {
        $node = new SelectorWeightBuilder('SelectorRandom',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Return a builder that allows fixed number of iterations per type.
      *
      *  @access public
      *  @return Faker\Components\Engine\Entity\Builder\Selector\SelectorSwapBuilder
      */
    public function selectorSwap()
    {
        $node = new SelectorSwapBuilder('SelectorRandom',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Return a builder that allows combination of types to combine in a single return value
      *
      *  @access public
      *  @return Faker\Components\Engine\Entity\Builder\NodeBuilder
      */    
    public function combination()
    {
        # this is a little evil (typebuilder is child of nodebuilder) but avoid alot of copy and paste
        $node = new NodeBuilder($this->name,$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        return $node;
    }
   
}
/* End of File */