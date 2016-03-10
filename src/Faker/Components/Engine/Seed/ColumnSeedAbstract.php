<?php
namespace Faker\Components\Engine\Seed;

use \ReflectionClass;
use \ReflectionMethod;
use Faker\Project;
use Faker\Components\Engine\EngineException; 
use Faker\Components\Engine\DB\Builder\ColumnBuilder;
use Faker\Components\Engine\DB\Builder\TableBuilder;
use Faker\Components\Engine\DB\Builder\FieldBuilder;
use Faker\Components\Engine\DB\Builder\ForeignKeyBuilder;


abstract class ColumnSeedAbstract extends SeedAbstract
{
    
    
    //--------------------------------------------------------------------------
    # DBAL Type
    
    /**
     * Doctrine dbal type to bind to the column
     * 
     * See Faker\Components\Engine\Common\TypeRepository for a list of values to use
     * 
     * May also use extension bootstrap to add your own
     * 
     * @return string
     */ 
    protected function getDBALType()
    {
        throw new EngineException('Column seed requires '.__METHOD__);
    }
    
    //--------------------------------------------------------------------------
    # Column Name
    
    /**
     * A unique name for this column in the dest table
     * 
     * @return string
     */ 
    protected function getColumnName()
    {
        throw new EngineException('Column seed requires '.__METHOD__);
    }
    
    
    //--------------------------------------------------------------------------
    # Configure fields
    
    /**
     * Configure the fieldbuilder 
     * 
     * @return void
     * @param FieldBuilder  $oFieldBuilder The Fieldbuilder that create the fields this column use during generation 
     * @param  array        $aOptions      runtime options that are use to configure each field the array format up to you
     */ 
    protected function configureField(FieldBuilder $oFieldBuilder, array $aOptions) 
    {
        return null;
    }
    
    
    //--------------------------------------------------------------------------
    # Virtual Constuctors
    
    
    /**
     * Uses reflection to load constructors for foreign keys.
     * 
     * Implementation signature example protected function foreignKeyNameX(ForeignKeyBuilder $oKeyBuilder,array $aOptions) {};
     * 
     * @return array of class methods to call
     * 
     */ 
    protected function getForeignKeys()
    {
        $oReflector = new ReflectionClass($this);
        
        $aMethodsFound = array();
        foreach($oReflector->getMethods(ReflectionMethod::IS_PROTECTED) as $sMethod) {
            if(0 === strpos($sMethod->name,'foreignKey',0)) {
                
                $aMethodsFound[] = $sMethod->name;
            }
            
        }
        
        return $aMethodsFound;
    }
    
    //--------------------------------------------------------------------------
    # public methods
    
    
    /**
     * Build the column using the def in this class
     * 
     * @return void
     * @param ColumnBuilder $oBuilder
     * @param array         $aOptions
     */ 
    public function build(TableBuilder $oBuilder, array $aOptions)
    {
        $oContainer    = $this->getContainer();
        
        # create new column builder
        $oColumnBuilder = $oBuilder->addColumn($this->getColumnName());
        
        # configure override locale
        $sNewLocalType = $this->getLocaleType();
        if($sNewLocalType !== null) {
            $oNewLocale = $oContainer->getLocaleFactory()->create($sNewLocalType);
            $oColumnBuilder->defaultLocale($oNewLocale);
        }
        
        # configure override random generators
        $sNewDefaultRandomType = $this->getRandomGeneratorType();
        if($sNewLocalType !== null) {
            $oNewDefaultRandomType = $oContainer->getRandomGeneratorFactory()->create($sNewDefaultRandomType);
            $oColumnBuilder->defaultGenerator($oNewDefaultRandomType);
        }
        
        # configuer the dbal type
        $oColumnBuilder->dbalType($this->getDBALType());
        
        # configure the field
        $oFieldBuilder = $oColumnBuilder->addField();
        $this->configureField($oFieldBuilder,$aOptions);
        $oFieldBuilder->end();
        
        # construct the Foreign keys
        foreach( $this->getForeignKeys() as $sForeignKey) {
            $oKeyBuilder = $oColumnBuilder->addForeignField();
            $this->$sForeignKey($oKeyBuilder,$aOptions);
            $oKeyBuilder->end();
        }
        
        # finish constucting the node
        $oColumnBuilder->end();
        
        # return table builder for fluid interface
        return $oBuilder;
    }
    
}
/* End of class */