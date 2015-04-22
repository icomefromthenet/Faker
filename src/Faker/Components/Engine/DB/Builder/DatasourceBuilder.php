<?php
namespace Faker\Components\Engine\DB\Builder;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;


use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Builder\NodeCollection;
use Faker\Components\Engine\Common\Datasource\DatasourceRepository;
use Faker\Components\Engine\Common\Datasource\PHPDatasource;

/**
  *  Factory for Datasources
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class DatasourceBuilder extends NodeCollection
{
    /**
      *  @var Faker\Components\Engine\Common\Datasource\DatasourceRepository
      */
    protected $repo;
    
     /**
      *  Class Constructor
      *
      *  @access public
      *  @return void
      */
    public function __construct($name,
                                EventDispatcherInterface $event,
                                TypeRepository $repo,
                                Utilities $util,
                                GeneratorInterface $generator,
                                LocaleInterface $locale,
                                Connection $conn,
                                Loader $loader,
                                DatasourceRepository $datasourceRepo)
    {
        parent::__construct($name,$event,$repo,$util,$generator,$locale,$conn,$loader);
        
        $this->repo = $datasourceRepo;
        
    }
    
    
    public function getNode()
    {
        return null;
    }
 
    public function end()
    {
        $parent   = $this->getParent();
        $children = $this->children();
        
        foreach($children as $formatter) {
            $parent->append($formatter);
        }
        
        return $parent;
    }
    
    //------------------------------------------------------------------
    # Builders
    
    /**
      *  Returns a datasource definition 
      *
      *  @return Faker\Components\Engine\Common\Datasource\AbstractDefinition
      *  @access public
      */
    public function customDatasource($alias)
    {
        if(($resolvedAlias = $this->repo->find($alias)) === null) {
            throw new EngineException("$alias not found in datasource definition repository unable to create definition");
        }
        
        
        $field = new $resolvedAlias();
        
        
        # set the basic fields need by each type
        $field->generator($this->generator);
        $field->utilities($this->utilities);
        $field->database($this->database);
        $field->locale($this->locale);
        $field->eventDispatcher($this->eventDispatcher);
        //$field->templateLoader($this->templateLoader);
        $field->setParent($this);
        
        # return the definition for configuration by user
        return $field;
    }
    
    /**
      *  Returns a file datasource definition 
      *
      *  @return Faker\Components\Engine\Common\Datasource\AbstractDefinition
      *  @access public
      */
    public function createFileSource()
    {
        return $this->customDatasource('filesource');          
    }
    
    /**
      *  Returns a sql datasource definition 
      *
      *  @return Faker\Components\Engine\Common\Datasource\AbstractDefinition
      *  @access public
      */
    public function createSQLSource()
    {
        return $this->customDatasource('sqlsource');              
    }
    
    /**
      *  Returns a php datasource definition 
      *
      *  @return Faker\Components\Engine\Common\Datasource\PHPDatasource
      *  @access public
      */
    public function createPHPSource()
    {
        return $this->customDatasource('phpsource');      
    }
  
}
/* End of File */