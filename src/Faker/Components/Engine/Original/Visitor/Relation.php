<?php
namespace Faker\Components\Engine\Original\Visitor;

/*
 * class Relation
 *
 * Represents a relationship between two columns
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class Relation 
{
    
    protected $table;
    
    protected $column;
    
    protected $container;
    
    
    /*
     * __construct()
     *
     * @param string $table the name of the local table
     * @param string $column string name of the local column
     * @param string $container string the name of the local container or null
     */
    public function __construct($table,$column,$container = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->container = $container;
    }
    
    
    //------------------------------------------------------------------
    # Accessors
    
    public function getTable()
    {
        return $this->table;
    }
    
    public function getColumn()
    {
        return $this->column;
    }
    
    public function getContainer()
    {
        return $this->container;
    }
    
    //------------------------------------------------------------------
}
/* End of File */