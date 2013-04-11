<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\Common\Utilities;
use PHPStats\Generator\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface as CompositeGenInterface;

/**
 * Interface for a type generator.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
interface TypeInterface extends CompositeGenInterface
{
    
    /**
      *  Get the utilities property
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Utilities
      */ 
    public function getUtilities();
    
    
    /**
      *  Sets the utilities property
      *
      *  @access public
      *  @param $util Faker\Components\Engine\Common\Utilities
      */
    public function setUtilities(Utilities $util);
    
    
    /**
      *  Fetch the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function getGenerator();
    
    /**
      *  Set the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function setGenerator(GeneratorInterface $generator);

    /**
      *  Set the type with a locale
      *
      *  @access public
      *  @param Faker\Locale\LocaleInterface $locale
      */
    public function setLocale(LocaleInterface $locale);
    
    /**
      * Fetches this objects locale
      * 
      *  @return Faker\Locale\LocaleInterface
      *  @access public
      */
    public function getLocale();
   
}
/* End of File */