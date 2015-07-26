<?php
namespace Faker\Components\Engine\Seed;

use Faker\Project;
use Faker\Components\Engine\Exception as EngineException;

abstract class SeedAbstract
{
    
    protected $oContainer;
    
    
    /**
     * Return the project DI Container 
     * 
     * @return Project
     */
    protected function getContainer()
    {
       return $this->oContainer; 
    }
    
    
    //--------------------------------------------------------------------------
    # Locale
    
    /**
     * Return the string that identify a new locale to use
     * 
     * See Faker\Locale\LocaleFactory for a list of values to use 
     * 
     * May also use extension bootstrap to add your own
     * 
     * @return string
     */ 
    protected function getLocaleType()
    {
        return null;
    }
    
    //--------------------------------------------------------------------------
    # Random Generator
    
    /**
     * Return a string that identity a new random generator to use
     * 
     * See PHPStats\Generator\GeneratorFactory for list of values to use
     * 
     * May also use extension bootstrap to add your own
     * 
     * @return string
     */ 
    protected function getRandomGeneratorType()
    {
        return null;
    }
    
   
   
    
    //--------------------------------------------------------------------------
    # public methods
    
    /**
     *  Class constructor
     *  
     * @param Project $oContainer
     * @return void
     */ 
    public function __construct(Project $oContainer)
    {
        $this->oContainer = $oContainer;
    }
    
    
 
}
/* End of class */