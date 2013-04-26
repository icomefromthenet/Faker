<?php
namespace Faker\Components\Engine\Entity\Builder\Selector;

use Faker\Components\Engine\Common\Builder\SelectorRandomBuilder as Base;

/**
  *  Allows the Random Selector to be created and populated with types
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorRandomBuilder extends Base
{
    
    /**
    * Returns the parent node.
    *
    * @return Faker\Components\Engine\Entity\Builder\NodeBuilder
    */
    public function end()
    {
        return parent::end();
    }
    
  
}
/* End of file */
