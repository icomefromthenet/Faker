<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Entity\Composite\FieldNode;

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
    *  @return NodeInterface The parent builder 
    *  @access public
    */
    public function endDescribe()
    {
        return $this->end();
    }
   
    /**
    *  Send the child compositeNodes to parent builder
    *
    *  @return NodeInterface The parent builder 
    *  @access public
    */
    public function endCombination()
    {
        return $this->end();
    }
  
    
    
    /**
    *  Send the child compositeNodes to parent builder
    *
    *  @return NodeInterface The parent builder 
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
      *  @return \Faker\Components\Engine\Common\Builder\TypeDefinitionInterface
      */    
    public function find($alias)
    {
        if(($resolvedAlias = $this->repo->find($alias)) === null) {
            throw new EngineException("$alias not found in type repository unable to create");
        }
        
        $field = new $resolvedAlias();
        
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
      *  @return Faker\Components\Engine\Common\Builder\AlphaNumericTypeDefinition
      *  @access public   
      */
    public function fieldAlphaNumeric()
    {
        return $this->find('alphanumeric');
    }
    
    /**
      *  Return an  field for configuration
      *  
      *  @return \Faker\Components\Engine\Common\Builder\AutoIncrementTypeDefinition
      *  @access public   
      */
    public function fieldAutoIncrement()
    {
        return $this->find('autoincrement');
    }
    
    /**
      * Return an boolean field for configuration
      * 
      *  @return \Faker\Components\Engine\Common\Builder\BooleanTypeDefinition
      *  @access public   
      */
    public function fieldBoolean()
    {
        return $this->find('boolean');
    }
    
    /**
      *  Return an City field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\CitiesTypeDefinition
      *  @access public
      */
    public function fieldCity()
    {
        return $this->find('city');
    }
    
    /**
      *  Return an Constant field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\ConstantNumberTypeDefinition 
      *  @access public   
      */
    public function fieldConstant()
    {
        return $this->find('constant_number');
    }
    
    /**
      *  Return an Country field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\CountryTypeDefinition
      *  @access public
      */
    public function fieldCountry()
    {
        return $this->find('country');
    }
    
    /**
      * Return an Date field for configuration
      * 
      *  @return \Faker\Components\Engine\Common\Builder\DateTypeDefinition
      *  @access public
      */
    public function fieldDate()
    {
        return $this->find('date');
    }
    
    /**
      * Return an Email field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\EmailTypeDefinition
      *  @access public
      */
    public function fieldEmail()
    {
        return $this->find('email');
    }
    
    /**
      * Return a People field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\NamesTypeDefinition
      *  @access public
      */
    public function fieldPeople()
    {
        return $this->find('people');
    }
    
    /**
      * Return a Null field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\NullTypeDefinition
      *  @access public
      */
    public function fieldNull()
    {
        return $this->find('nulltype');
    }
    
    /**
      * Return a Numeric field for configuration
      *  
      *  @return \Faker\Components\Engine\Common\Builder\NumericTypeDefinition
      *  @access public
      */
    public function fieldNumeric()
    {
        return $this->find('numeric');
    }
    
    /**
     *  Return an Range Definition
     *
     *  @return \Faker\Components\Engine\Common\Builder\RangeTypeDefinition
     *  @access public   
     */
    public function fieldRange()
    {
        return $this->find('range');
    }
    
    /**
      *  Return a Regex field for configuration
      * 
      *  @return \Faker\Components\Engine\Common\Builder\RegexTypeDefinition
      *  @access public   
      */
    public function fieldRegex()
    {
        return $this->find('regex');
    }
    
    /**
      * Return a Template field for configuration
      * 
      * @return \Faker\Components\Engine\Common\Builder\TemplateTypeDefinition
      * @access public
      */
    public function fieldTemplate()
    {
        return $this->find('template');
    }
    
    /**
      * Return a Text field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\TextTypeDefinition
      *  @access public   
      */
    public function fieldText()
    {
        return $this->find('text');
    }
    
    /**
      *  Return a Unique Number  field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\UniqueNumberTypeDefinition
      *  @access public   
      */
    public function fieldUniqueNumber()
    {
        return $this->find('unique_number');
    }
    
    /**
      *  Return a Unique String field for configuration
      *  
      *  @return \Faker\Components\Engine\Common\Builder\UniqueStringTypeDefinition
      *  @access public
      */
    public function fieldUniqueString()
    {
        return $this->find('unique_string');
    }
    
    /**
      *  Return a closure type for configuration
      *
      *  @access public
      *  @return \Faker\Components\Engine\Common\Builder\ClosureTypeDefinition
      */
    public function fieldClosure()
    {
        return $this->find('closure');
    }
    
    /**
      *  Return a FromDatasource type for configuration.
      *
      *  @access public
      *  @return \Faker\Components\Engine\Common\Builder\FromSourceTypeDefinition
      */
    public function fieldFromSource()
    {
        return $this->find('fromsource');
    }
    
     /**
      *  Return a alternate selector builder that alternatve of values
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorAlternateBuilder
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
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorRandomBuilder
      */
    public function selectorRandom()
    {
        $node = new SelectorRandomBuilder('SelectorRandom',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Depreciated Alias to self::selectorWeight
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorWeightBuilder
      *  @depreciated
      */
    public function selectorWeightAlternate()
    {
        $node = new SelectorWeightBuilder('SelectorWeight',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Return a builder that allows alternation that preferences the left or right value.
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorWeightBuilder
      */
    public function selectorWeight()
    {
        $node = new SelectorWeightBuilder('SelectorWeight',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Return a builder that allows fixed number of iterations per type.
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorSwapBuilder
      */
    public function selectorSwap()
    {
        $node = new SelectorSwapBuilder('SelectorSwap',$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Return a builder that allows combination of types to combine in a single return value
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\TypeBuilder
      */    
    public function combination()
    {
        # this is a little evil (typebuilder is child of nodebuilder) but avoid alot of copy and paste
        $node = new TypeBuilder($this->name,$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        return $node;
    }
    
     /**
     *  Execute The closure over an array.
     *
     *  Helper so dont have to break chaning to apply
     *  same operation to array of values.
     *
     *  @access public
     *  @return NodeBuilder
     *  @param array $array of values
     *  @param Closure the function to execute
     *
    */
    public function each(array $array,\Closure $func)
    {
        foreach($array as $v) {
            $func($v,$this);
        }
        
        return $this;
    }
   
   
}
/* End of File */
