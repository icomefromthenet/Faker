<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Text;

/**
  *  Definition for the Text Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TextTypeDefinition extends AbstractDefinition
{
    
    public function endTextField()
    {
        return $this->end();
    }
      
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\TypeNode The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $type = new Text();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Text',$this->eventDispatcher,$type);
    }
    
    /**
      *  Sets the number of paragraphs to generate
      *
      *  @access public
      *  @param integer value
      *  @example $type->numberParagraphs(3);
      *  @return TextTypeDefinition
      */
    public function numberParagraphs($value)
    {
        $this->attribute('paragraphs',$value);
        
        return $this;
    }
    
    
    /**
      *  Set the minimum number of lines in a paragraph
      *
      *  @access public
      *  @return TextTypeDefinition
      *  @example $type->minLines(3);
      *  @param integer $value the min repeat value 
      *  
      */
    public function minLines($value)
    {
        $this->attribute('minlines',$value);
        return $this;
    }
    
    /**
      *  Set the minimum number of lines in a paragraph
      *
      *  @access public
      *  @return TextTypeDefinition
      *  @param integer $value the max value
      *  @example $type->maxLines(5);
      *  
      */
    public function maxLines($value)
    {
        $this->attribute('maxlines',$value);
        return $this;
    }
}
/* End of File */