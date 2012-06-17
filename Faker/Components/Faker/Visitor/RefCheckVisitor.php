<?php
namespace Faker\Components\Faker\Visitor;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Exception as FakerException;

/*
 * class RefCheckVisitor
 *
 * Will check if a reference exists (table->column ref exists)
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class RefCheckVisitor extends BaseVisitor
{
    
    /**
      *  @var string the table name 
      */
    protected $table;
    
    /**
      *  @var string the column name 
      */
    protected $column;
    
    /**
      *  @var CompositeInterface the column object 
      */
    protected $obj;
    
    
    public function __construct($table,$column)
    {
        $this->table    = $table;
        $this->column   = $column;
        $this->obj      = null;
    }
    
    
    //------------------------------------------------------------------
    # Visitor Methods
    
    public function visitCacheInjector(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitRefCheck(CompositeInterface $composite)
    {
        
        if($composite instanceof Column) {
            
            $table = $composite->getParent();
            
            if($this->table === $table->getId() && $this->column === $composite->getId()) {
                # set the obj container to reference the matched column
                $this->obj = $composite;                
            }
            
        }
        
    }
    
    public function visitMapBuilder(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    //------------------------------------------------------------------

    /**
      *  Fetch the found column reference
      *
      *  @access public
      *  @return CompositeInterface|null if not found
      */
    public function getFoundColumn()
    {
        return $this->obj;
    }
    
    //------------------------------------------------------------------
}
/* End of File */