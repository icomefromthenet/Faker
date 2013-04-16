<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Builder\DefaultTypeDefinition;
use Faker\Components\Engine\Entity\Composite\FieldNode;
use Faker\Components\Engine\Common\Composite\CompositeInterface;

/**
  *  Builder to construct a .
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
    protected function find($alias)
    {
        if(($resolvedAlias = $this->repo->find($alias)) === null) {
            throw new EngineException("$alias not found in type repository unable to create");
        }
        
        $field = new $resolvedAlias();
        
        # set the basic fields need by each type
        $field->generator($this->generator);
        $field->utilities($this->utilities);
        $field->database($this->connection);
        $field->locale($this->locale);
        $field->eventDispatcher($this->eventDispatcher);
        $field->templateLoader($this->templateLoader);
        $field->setParent($this);
        
        # return the definition for configuration by user
        return $field;
    }
    
    
    public function fieldAlphaNumeric()
    {
        return $this->find('alphanumeric');
    }
    
    public function fieldAutoIncrement()
    {
        return $this->find('autoincrement');
    }
    
    public function fieldBoolean()
    {
        return $this->find('boolean');
    }
    
    public function fieldCity()
    {
        return $this->find('city');
    }
    
    public function fieldConstant()
    {
        return $this->find('constant_number');
    }
    
    public function fieldCountry()
    {
        return $this->find('country');
    }
    
    public function fieldDate()
    {
        return $this->find('date');
    }
    
    public function fieldEmail()
    {
        return $this->find('email');
    }
    
    public function fieldPeople()
    {
        return $this->find('people');
    }
    
    public function fieldNull()
    {
        return $this->find('nulltype');
    }
    
    public function fieldNumeric()
    {
        return $this->find('numeric');
    }
    
    public function fieldRange()
    {
        return $this->find('range');
    }
    
    public function fieldRegex()
    {
        return $this->find('regex');
    }
    
    public function fieldTemplate()
    {
        return $this->find('template');
    }
    
    public function fieldText()
    {
        return $this->find('text');
    }
    
    public function fieldUniqueNumber()
    {
        return $this->find('unique_number');
    }
    
    public function fieldUniqueString()
    {
        return $this->find('unique_string');
    }

    public function fieldClosure()
    {
        return $this->find('closure');
    }
    
    
    /**
      *  Return a alternate selector builder that alternatve of values
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorAlternateBuilder
      */    
    public function selectorAlternate()
    {
        $node = new SelectorAlternateBuilder('SelectorAlternate',$this->event,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
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
        $node = new SelectorRandomBuilder('SelectorRandom',$this->event,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $node->setParent($this);
        
        return $node;
    }
    
    /**
      *  Return a builder that allows alternation that preferences the left or right value.
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorWeightBuilder
      */
    public function selectorWeightAlternate()
    {
        $node = new SelectorWeightBuilder('SelectorRandom',$this->event,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
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
        $node = new SelectorSwapBuilder('SelectorRandom',$this->event,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
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
   
}
/* End of File */