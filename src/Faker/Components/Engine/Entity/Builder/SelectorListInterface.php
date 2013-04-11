<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Components\Engine\EngineException;


/**
  *  List of selectors 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface SelectorListInterface 
{
    
    
    public function selectorAlternate();
    
    
    public function selectorRandom();
    
    
    public function selectorWeightAlternate();
    
    
    public function selectorSwap();
    
}
