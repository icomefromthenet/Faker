<?php 
namespace Faker\Components\Engine\Common\Datasource;

use Faker\ExtensionInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\EngineException;
use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\Common\Datasource\AbstractDefinition;
use Faker\Components\Engine\Common\Composite\DatasourceNode;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;



/**
  *  Factory for Datasource Definitions, this builder will accept all
  *  external dep that each definition requires to build a datasource,
  *  though the actual construction of the datasource is done be the
  *  definition.
  *
  *  The Repository is used to fetch a list of definitions
  * 
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class DatasourceBuilder 
{
    
    protected $parent;
    
    protected $utilities;
    
    protected $locale;
    
    protected $generator;
    
    protected $eventDispatcher;
    
    protected $database;
    
    protected $templateLoader;
    
    protected $datasourceRepository;
    
    /**
     * Return the datasource builder repositry
     * 
     * @return Faker\Components\Engine\Common\Datasource\DatasourceRepository
     */ 
    protected function getRepository()
    {
        return $this->datasourceRepository;
    }
    
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @return void
      */
    public function __construct(EventDispatcherInterface $event,
                                Utilities $util,
                                GeneratorInterface $generator,
                                LocaleInterface $locale,
                                Connection $conn,
                                Loader $loader,
                                ExtensionInterface $sourceRepository
                                )
    {
        
        $this->eventDispatcher      = $event;
        $this->database             = $conn;
        $this->generator            = $generator;
        $this->utilities            = $util;
        $this->locale               = $locale;
        $this->templateLoader       = $loader;
        $this->datasourceRepository = $sourceRepository;
    }
    
    /**
     * Create a new datasource composite node, fetch the builder or source
     * from the repository and instance the datasource and wrap into a composite node
     * 
     * @return Faker\Engine\Common\Composite\CompositeInterface
     */ 
    public function createSource($componentName)
    {
        $source = null;
        
        if($builderName = $this->datasourceRepository->find($componentName)) {
            
            $builder = new $builderName();
            
            # instanced a builder or the datasource directly
            if($builder instanceof AbstractDefinition) {
                $builder->eventDispatcher($this->eventDispatcher);
                $builder->database($this->database);
                $builder->utilities($this->utilities);
                $builder->templateLoader($this->templateLoader);
                $builder->locale($this->locale);
                $builder->generator($this->generator);
                
                # build the composite node
                $source = $builder->getNode();
               
            }
            else {
                $builder->setUtilities($this->util);
                $builder->setGenerator($this->defaultGenerator);
                $builder->setLocale($this->defaultLocale);
                $builder->setEventDispatcher($this->eventDispatcher);
                $source = $builder;
            }
            
            $typeNode = new DatasourceNode($componentName,$this->eventDispatcher,$source); 
        }
        else {
            throw new EngineException('The datasource at name::'.$componentName .' is not registered in the DatasourceRepository');
        }

        return $typeNode;
    }
    

}
/* End of File */