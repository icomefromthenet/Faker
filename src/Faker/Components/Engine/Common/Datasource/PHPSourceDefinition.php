<?php
namespace Faker\Components\Engine\Common\Datasource;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;


/**
  *  Definition For PHP Datasources 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class PHPSourceDefinition extends AbstractDefinition
{
    protected $dataIterator;
    protected $dataClosure;
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        if($this->dataIterator === null && !empty($this->dataClosure)) {
            $this->dataIterator = $this->dataClosure->__invoke();
        }
        
        $source = new PHPDatasource();
        
        $source->setIterator($this->dataIterator);
       
        return $source;
    }
    
    
    
    public function setDataIterator(\Iterator $it)
    {
        $this->dataIterator = $it;
        
        return $this;
    }
   
    
    public function setDataFromClosure(\Closure $closure)
    {
        $this->dataClosure = $closure;
        
        return $this;
    }
    
    public function setDataClosure(\Closure $closure)
    {
        $this->dataClosure = $closure;
        
        return $this;
    }
    
    public function endPHPSource()
    {
        return $this->end();
    }
}
/* End of File */