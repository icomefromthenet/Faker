<?php
namespace Faker\Components\Engine\Seed;

use Faker\Components\Engine\Entity\Builder\EntityGenerator;
use Faker\Components\Engine\Entity\Builder\FieldCollection;
use Faker\Components\Engine\Common\Builder\NodeBuilder;
use Faker\Components\Engine\EngineException; 

/**
 *  Used to store the configuration of an entity that uses the EntityGenerator
 *
 *  @author Lewis Dyer <getintouch@icomefromthenet.com>
 */
class AbstractEntitySeed
{
    #------------------------------------------------------------------------
    # Constants
   
    
    //const ENTITY_NAME                    = 'voucher';
    //const FIELD_VOUCHER_NAME             = 'voucher_name';
    
    #------------------------------------------------------------------------
    # Properties
   
    
    /**
     * @var Faker\Components\Engine\Entity\Builder\EntityGenerator
    */
    protected $entity;
    
    
    //-------------------------------------------------------
    # Constructor
    
    public function __construct(EntityGenerator $generator, \Closure $formatter)
    {
        $this->entity    = $generator;

        $fieldCollection = $generator->describe();
        
        foreach($this->getFields() as $fieldConstant) {
            $this->callConfigureMethod($fieldConstant,$fieldCollection);
        }
        
        $fieldCollection->end();
        
        $this->entity->map($formatter);

    }
    
    //-------------------------------------------------------
    # Helpers
    
    protected function getFields()
    {
        $oClass = new \ReflectionClass($this);
        
        return array_values(array_filter(array_flip($oClass->getConstants()),function($val) {
           return substr($val, 0, 6) === "FIELD_";
        }));
        
    }

    protected function callConfigureMethod($constant,$builder)
    {
        $split = explode('_', constant("self::$constant"));
        
        if($split[0] === 'FIELD') {
            unset($method[0]);    
        }
        
        $split = array_map(function($val) {
            return ucfirst($val);
        },$split);
        
        $method = 'configure'. implode($split,'');
        if(!method_exists($this,$method)) {
            throw new \RuntimeException("Method::$method does not exist");
        }
        
        $this->$method($builder);
        
        return $this;
    }
    
    
    //-------------------------------------------------------
    #  Methods configure the fields for the entity
    
    
    /**
     *  Return a collection of voucher GenericEntities
     *
     *  @access public
     *  @return  Faker\Components\Engine\Entity\EntityIterator
     *
    */
    public function make($number)
    {
        return $this->entity->fake($number);
    }
    
}
/* End of Class */

