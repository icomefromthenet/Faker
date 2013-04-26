<?php
namespace Faker\Components\Engine\Entity\Builder\Type;

use Faker\Components\Engine\Common\Builder\NamesTypeDefinition as Base;

/**
  *  Definition for the Names Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class NamesTypeDefinition extends Base
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