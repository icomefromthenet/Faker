<?php
namespace Faker\Components\Engine\Seed;

use \ReflectionClass;
use \ReflectionMethod;
use Faker\Project;
use Faker\Components\Engine\EngineException; 
use Faker\Components\Engine\DB\Builder\ColumnBuilder;
use Faker\Components\Engine\DB\Builder\TableBuilder;
use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Components\Engine\DB\Builder\FieldBuilder;
use Faker\Components\Engine\DB\Builder\ForeignKeyBuilder;
use Faker\Components\Engine\DB\Builder\DatasourceBuilder;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;

abstract class TableSeedAbstract extends SeedAbstract
{
    
    
    protected $iToGenerate;
    
    //--------------------------------------------------------------------------
    # Table Name
    
    /**
     * A unique name for this table in the dest schema
     * 
     * @return string
     */ 
    protected function getTableName()
    {
        throw new EngineException('Table seed requires '.__METHOD__);
    }
    
    
    //--------------------------------------------------------------------------
    # Table Columns
    
    /**
     * Return a array indexed with column name and callable
     * If the callable is an instance of ColumnSeedAbstract
     * it will be used or must me a method defined in this
     * class.
     * 
     * Callable might be :
     *  protected function columnX(ColumnBuilder $oColumnBuilder, array $aOptions)
     * 
     * The order that columns are returned in will be the order
     * they are genereted in.
     * 
     * @access protected
     * @return array
     */ 
    protected function getColumns()
    {
        throw new EngineException('Table seed requires '.__METHOD__);
    }
    
    //--------------------------------------------------------------------------
    # Schema Events
   
    
    protected function onGenerateStart(GenerateEvent $oEvent)
    {
        
    }
    
    
    protected function onGenerateEnd(GenerateEvent $oEvent)
    {
        
    }
    
    
    //--------------------------------------------------------------------------
    # Virtual Constuctors
    
    
    /**
     * Uses reflection to load constructors for datasources that will be attached to the schema builder
     * 
     * Implementation signature example 
     * 
     * protected function datasourceNameX(DatasourceBuilder $oKeyBuilder,array $aOptions) {};
     * 
     * @return array of class methods to call
     * 
     */ 
    protected function getDatasources()
    {
        $oReflector = new ReflectionClass($this);
        
        $aMethodsFound = array();
        foreach($oReflector->getMethods(ReflectionMethod::IS_PROTECTED) as $sMethod) {
            if(0 === strpos($sMethod->name,'datasource',0)) {
                
                $aMethodsFound[] = $sMethod->name;
            }
            
        }
        
        return $aMethodsFound;
    }
    
    //--------------------------------------------------------------------------
    # public methods
    
    /**
     *  Class constructor
     *  
     * @param Project $oContainer
     * @return void
     */ 
    public function __construct(Project $oContainer,$iToGenerate)
    {
        parent::__construct($oContainer);
        $this->iToGenerate = $iToGenerate;
    }
    
    
    /**
     * Build the table using the def in this class
     * 
     * @return void
     * @param ColumnBuilder $oBuilder
     * @param array         $aOptions
     */ 
    public function build(SchemaBuilder $oBuilder, array $aOptions)
    {
        $oContainer    = $this->getContainer();
        $aColumns      = $this->getColumns();
        
        # fetch the table collection.
        $oTableCollection = $oBuilder->describe();
        
        $oTableBuilder = $oTableCollection->addTable($this->getTableName());
        $oTableBuilder->toGenerate($this->iToGenerate);
        
        # configure override locale
        $sNewLocalType = $this->getLocaleType();
        if($sNewLocalType !== null) {
            $oNewLocale = $oContainer->getLocaleFactory()->create($sNewLocalType);
            $oTableBuilder->defaultLocale($oNewLocale);
        }
        
        # configure override random generators
        $sNewDefaultRandomType = $this->getRandomGeneratorType();
        if($sNewLocalType !== null) {
            $oNewDefaultRandomType = $oContainer->getRandomGeneratorFactory()->create($sNewDefaultRandomType);
            $oTableBuilder->defaultGenerator($oNewDefaultRandomType);
        }
        
        
        # bind events
        $oBuilder->onGenerateStart(array($this, 'onGenerateStart'));
        $oBuilder->onGenerateEnd(array($this,'onGenerateEnd'));


        # construct datasources
        foreach($this->getDatasources() as $sMethod) {
            $oDatasourceBuilder = $oBuilder->addDatasource();
                $this->$sMethod($oDatasourceBuilder,$aOptions);
            $oDatasourceBuilder->end();
        }
        
        
        # build the columns
        foreach($this->getColumns() as $sColumnName => $oMethod) {
            if($oMethod instanceof ColumnSeedAbstract) {
                $oMethod->build($oTableBuilder,$aOptions);
            } else {
              $oColumnBuilder = $oTableBuilder->addColumn($sColumnName);  
                $this->$oMethod($oColumnBuilder,$aOptions);
              $oColumnBuilder->end();
            }    
        }
        
        # finish constucting the table
        $oTableBuilder->end();
        
        # return schema builder for fluid interface
        return $oBuilder;
    }
    
}
/* End of class */