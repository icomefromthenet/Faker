<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Template;

/**
  *  Definition for the Template Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TemplateTypeDefinition extends AbstractDefinition
{
    
    public function endTemplateField()
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
        $type = new Template($this->templateLoader);
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Template',$this->eventDispatcher,$type);
    }
    
    /**
      *  Set the template to the file in project template dir
      *
      *  @param string $file the template name
      *  @example $type->format('atemplate.twig');
      *  @return TemplateTypeDefinition
      *
      */
    public function useTemplateFile($file)
    {
        $this->attribute('file',$file);
        return $this;
    }
    
    /**
      *  Set the minimum number of times to repeat the format string
      *
      *  If a max is used and not idential as the min value a middle value chosen at random
      *  
      *  @access public
      *  @return TemplateTypeDefinition
      *  @example $type->useTemplateString('{{field1}} + {{field2}}');
      *  @param integer $value the min repeat value 
      *  
      */
    public function useTemplateString($template)
    {
        $this->attribute('template',$template);
        return $this;
    }
   
}
/* End of File */