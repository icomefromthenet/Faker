<?php
namespace Faker\Components\Engine\Entity\Builder\Type;

use Faker\Components\Engine\Common\Builder\ConstantStringTypeDefinition as Base;

/**
  *  Definition for the ConstantString Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ConstantStringTypeDefinition extends Base
{
    
    /*
     * Return the parent nodebuilder
     *
     * @return Faker\Components\Engine\Entity\Builder\NodeBuilder
     * @access public
     */
    public function end()
    {
        return parent::end();
    } 
    
}
/* End of File */