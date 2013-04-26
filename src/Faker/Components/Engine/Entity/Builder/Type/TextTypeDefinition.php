<?php
namespace Faker\Components\Engine\Entity\Builder\Type;

use Faker\Components\Engine\Entity\Builder\Type\TextTypeDefinition as BaseTextTypeDefinition;

/**
  *  Definition for the Text Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TextTypeDefinition extends BaseTextTypeDefinition
{
    
    /**
      *  Return the parent FieldBuilder
      *
      *  @access public
      *  @return Faker\Components\Engine\Entity\Builder\NodeBuilder
      */
    public function end()
    {
        return parent::end();
    }
    
    
}
/* End of File */