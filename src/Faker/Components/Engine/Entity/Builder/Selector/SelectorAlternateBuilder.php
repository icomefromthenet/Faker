<?php
namespace Faker\Components\Engine\Entity\Builder\Selector;

use Faker\Components\Engine\Common\Builder\SelectorAlternateBuilder as Base;

/**
  *  Allows the Alternate Selector to be created and populated with types
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorAlternateBuilder extends Base
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
