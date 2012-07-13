<?php
namespace Faker\Components\Faker\Visitor;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Composite\Table,
    Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\ForeignKey,
    Faker\Components\Faker\GeneratorCache,
    Faker\Components\Faker\CacheInterface,
    Faker\Components\Faker\Exception as FakerException;

/*
 * class ForeignInjectorVisitor
 *
 * Will inject a cache into a column or fk
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class ForeignCacheInjectorVisitor extends BaseVisitor
{
    
    /**
      *  @var GeneratorCache the cache to inject 
      */
    protected $cache;
    
    /**
      *  @var string the table name 
      */
    protected $table;
    
    /**
      *  @var string the column name 
      */
    protected $column;
    
    /**
      *  @var string the name of the fk type 
      */
    protected $fk_name;
    
    
    /**
      *  @var boolean used to determine if operation was completed 
      */
    protected $injected = false;
    
    /**
      *  Class Constructor
      *
      *  @return void
      *  @access public
      *  @param GeneratorCache $cache the cache to apply
      *  @param string $table the id of the table
      *  @param string $column the id of the column
      *  @param string $fk the id of the fk container (optional)
      */
    public function __construct(GeneratorCache $cache,$table,$column,$fk = null)
    {
        $this->cache    = $cache;
        $this->table    = $table;
        $this->column   = $column;
        $this->fk_name  = $fk;
        $this->injected = false;

    }
    
    
    //------------------------------------------------------------------
    # Visitor Methods
    
    /**
      *  Inject the cache if node matches the address
      *  
      *  @access public
      *  @param CompositeInterface the composite to operate on.
      *
      */
    public function visitCacheInjector(CompositeInterface $composite)
    {
       $done = false;
        
        # only check fk columns
        if($composite instanceof ForeignKey) {
        
            $column = $composite->getParent();
            $table  = $column->getParent();
            
            # does the table and column name match the index
            if($this->table === $table->getId() && $this->column === $column->getId() && $this->fk_name === $composite->getId()) {
                                
                # does the column have cache interface
                if(!$composite instanceof CacheInterface) {
                    throw new FakerException('Address '. $this->table .'.'. $this->column .' does not implement CacheInterface');
                }
                
                #tell column to cache values
                $composite->setUseCache(true); // should be set by default but won't harm code to set again.
                $composite->setGeneratorCache($this->cache);
                    
            }
        
        }
        
        return $done;
    }
    
    public function visitRefCheck(CompositeInterface $composite)
    {
        throw new FakerException('Not implemented');
    }
    
    public function visitMapBuilder(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitGeneratorInjector(CompositeInterface $composite)
    {
         throw new FakerException('Not implemented');
    }

    public function visitLocale(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }

}
/* End of File */