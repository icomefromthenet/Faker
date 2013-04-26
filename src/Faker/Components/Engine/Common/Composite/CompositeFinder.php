<?php
namespace Faker\Components\Engine\Common\Composite;

use Faker\Components\Engine\EngineException;

/**
  *  Finder class for composite queries
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class CompositeFinder
{
    /**
      *  @var Faker\Components\Engine\Common\Composite\CompositeInterface 
      */    
    protected $head;
    
    
    /**
      *   Set the composite to query
      *
      *   @param CompositeInterface $composite
      *   @return CompositeFinder
      *   @param public
      */
    public function set(CompositeInterface $composite)
    {
        $this->head = $composite;
        
        return $this;
    }
    
    /**
      *  Fetch the selected head
      *
      *  @access public
      *  @return CompositeInterface
      */    
    public function get()
    {
        return $this->head;
    }
    
    /**
      *  Clears the finder for next query
      *
      *  @return CompositeFinder
      *  @access public
      */
    public function clear()
    {
        $this->head = null;
        
        return $this;
    }
    
    /**
      *   Fetches the parent table
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws EngineException
      */
    public function parentTable()
    {
        # can't search schema for a parent table        
        if($this->head instanceof SchemaNode) {
            throw new EngineException('Has no parent table');
        }
        
        # if table is current head it must be the parentTable
        if($this->head instanceof TableNode) {
            return $this;
        }
        
        # iterate up till find the first table node
        do {
              $parent = $this->head->getParent();
                
                if($parent === null) {
                    $this->head = null;
                    break;
                }
                
                $this->head = $parent;
        }
        while(!$this->head instanceof TableNode);
        
        # return the finder
        return $this;
    }
    
    /**
      *   Fetches the parent column
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws EngineException
      */
    public function parentColumn()
    {
        if($this->head instanceof SchemaNode || $this->head instanceof TableNode) {
            throw new EngineException('Has no parent column');
        }
        
        do {
              $parent = $this->head->getParent();
                
                if($parent === null) {
                    $this->head = null;
                    break;
                }
                
                $this->head = $parent;
        }
        while(!$this->head instanceof ColumnNode);
        
        return $this;
    }
    
     /**
      *   Fetches the parent schema
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws EngineException
      */
    public function parentSchema()
    {
       if(!$this->head instanceof SchemaNode) {
            do {
                $parent = $this->head->getParent();
                
                if($parent === null) {
                    $this->head = null;
                    break;
                }
                
                $this->head = $parent;
            }
            while(!$this->head instanceof SchemaNode);
       }
        
       return $this; 
    }
    
    /**
      *   Fetches the table using name
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws EngineException
      */
    public function table($name)
    {
       if($this->head instanceof TableNode && $this->head->getId() === $name) {
            return $this;    
       } 
       
       # iterate up to schema
       if(!$this->head instanceof SchemaNode) {
            $this->parentSchema();
       }
       
       $schema = $this->head;
       #assume won't match to a table
       $this->head = null;
       
       # search for the table
       foreach($schema->getChildren() as $table) {
         if($table->getId() === $name) {
            $this->head = $table;
            break;
         }
        
       }
       
       
       return $this; 
    }
    
     /**
      *   Fetches the table column using name
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws EngineException
      */
    public function column($name)
    {
       # head a schema can't find a column, need under a table
       if($this->head instanceof SchemaNode) {
            $this->head = null;
            return $this;
       }
       
       # current head is the column search?
       if($this->head instanceof ColumnNode && $this->head->getId() === $name) {
            return $this;
       }
       
       # iterate up to table
       $this->parentTable();
       
       
       # find the table
       foreach($this->head->getChildren() as $column) {
         if($column->getId()=== $name) {
            $this->head = $column;
            break;
         }
        
       }
       
       # if head is not a column we can't have matched
       if(!$this->head instanceof ColumnNode) {
           $this->head = null;
       }
        
       return $this;
    }
    
}
/* End of File */