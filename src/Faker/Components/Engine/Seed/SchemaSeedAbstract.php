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
use Faker\Components\Engine\Common\Formatter\FormatterBuilder;


abstract class SchemaSeedAbstract extends SeedAbstract
{
    
    //--------------------------------------------------------------------------
    # Pass Name
    
    /**
     * A unique name for this pass
     * 
     * @return string
     */ 
    protected function getPassName()
    {
        throw new EngineException('Schema seed requires '.__METHOD__);
    }
    
    //--------------------------------------------------------------------------
    # Schema Name
    
    /**
     * The name of this schema
     * 
     * @return string
     */ 
    protected function getSchemaName()
    {
        throw new EngineException('Schema seed requires '.__METHOD__);
    }
    
    
    //--------------------------------------------------------------------------
    # Tables
    
    /**
     * Return a array indexed with table name and a callable
     * If the callable is an instance of TableSeedAbstract
     * it will be used or must me a method defined in this
     * class.
     * 
     * Callable might be :
     *  protected function tableX(TableBuilder $oTableBuilder, array $aOptions)
     * 
     * The order that tables are returned may not be same order they are
     * generated at, as foreign keys will be reorded to avoid reference errors
     * 
     * @access protected
     * @return array
     */ 
    protected function getTables()
    {
        throw new EngineException('Schema seed requires '.__METHOD__);
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
    
    /**
     * Uses reflection to load constructors for writters that will be attached to the schema builder
     * 
     * Implementation signature example 
     * 
     * protected function writterNameX(FormatterBuilder $oWriterBuilder,array $aOptions) {};
     * 
     * @return array of class methods to call
     * 
     */ 
    protected function getWritters()
    {
         $oReflector = new ReflectionClass($this);
        
        $aMethodsFound = array();
        foreach($oReflector->getMethods(ReflectionMethod::IS_PROTECTED) as $sMethod) {
            if(0 === strpos($sMethod->name,'writter',0)) {
                
                $aMethodsFound[] = $sMethod->name;
            }
            
        }
        
        return $aMethodsFound;
        
        
    }
    
    //--------------------------------------------------------------------------
    # public methods
    
    /**
     * Build the schema using the def in this class
     * 
     * @return void
     * @param array         $aOptions
     */ 
    public function build(array $aOptions)
    {
        $oContainer            = $this->getContainer();
        $aColumns              = $this->getTables();
        $oNewLocale            = null;
        $oNewDefaultRandomType = null;
        $sPassName             = $this->getPassName();
        $sSchemaName           = $this->getSchemaName();
        
        # configure override locale
        $sNewLocalType = $this->getLocaleType();
        if($sNewLocalType !== null) {
            $oNewLocale = $oContainer->getLocaleFactory()->create($sNewLocalType);
        }
        
        # configure override random generators
        $sNewDefaultRandomType = $this->getRandomGeneratorType();
        if($sNewLocalType !== null) {
            $oNewDefaultRandomType = $oContainer->getRandomGeneratorFactory()->create($sNewDefaultRandomType);
        }
        
        # create pass
        $oSchemaBuilder = $oContainer->addPass($sPassName,$sSchemaName,$oNewLocale,null,$oNewDefaultRandomType);
        
        # bind events
        $oSchemaBuilder->onGenerateStart(array($this, 'onGenerateStart'));
        $oSchemaBuilder->onGenerateEnd(array($this,'onGenerateEnd'));


        # construct datasources
        foreach($this->getDatasources() as $sMethod) {
            $oDatasourceBuilder = $oSchemaBuilder->addDatasource();
            $this->$sMethod($oDatasourceBuilder,$aOptions);
            $oDatasourceBuilder->end();
        }
        
        
        # build the tables
        foreach($this->getTables() as $sTableName => $oMethod) {
            if($oMethod instanceof TableSeedAbstract) {
                $oMethod->build($oSchemaBuilder,$aOptions);
            } else {
              $oTableBuilder = $oSchemaBuilder->describe()->addTable($sTableName);  
                $this->$oMethod($oTableBuilder,$aOptions);
              $oTableBuilder->end();
            }    
        }
        
        # finish constucting the tables
        $oSchemaBuilder->describe()->end();
        
        # build the writters
        foreach($this->getWritters() as $oMethod) {
            $oWritterBuilder = $oSchemaBuilder->addWriter();
            $this->$oMethod($oWritterBuilder,$aOptions);
            $oWritterBuilder->end();
        }
        
        # finish building the schema
        # return schema  for fluid interface
        return  $oSchemaBuilder->end();
    }
    
}
/* End of class */