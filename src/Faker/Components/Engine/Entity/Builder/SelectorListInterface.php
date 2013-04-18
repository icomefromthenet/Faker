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
    
    /**
      *  Return a alternate selector builder that alternatve of values
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorAlternateBuilder
      */    
    public function selectorAlternate();
    
     /**
      *  Return a builder that picks a type at random from the supplied list
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorRandomBuilder
      */
    public function selectorRandom();
    
    /**
      *  Return a builder that allows alternation that preferences the left or right value.
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorWeightBuilder
      */
    public function selectorWeightAlternate();
    
     /**
      *  Return a builder that allows fixed number of iterations per type.
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\SelectorSwapBuilder
      */
    public function selectorSwap();
    
    
     /**
      *  Return a builder that allows combination of types to combine in a single return value
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\TypeBuilder
      */    
    public function combination();
    
}
