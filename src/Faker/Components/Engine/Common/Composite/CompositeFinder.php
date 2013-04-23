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
        if($this->head instanceof SchemaNode) {
            throw new EngineException('Has no parent table');
        }
        
        do {
              $parent = $this->head->getParent();
                
                if($parent === null) {
                    return null;
                }
                
                $this->head = $parent;
        }
        while(!$this->head instanceof TableNode);
        
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
        if($this->head instanceof SchemaNode) {
            throw new EngineException('Has no parent column');
        }
        
        if($this->head instanceof TableNode) {
            throw new EngineException('Has no parent column');
        }
        
        do {
              $parent = $this->head->getParent();
                
                if($parent === null) {
                    return null;
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
                    return null;
                }
                
                $this->head = $parent;
            }
            while(!$this->head instanceof SchemaNode);
       }
        
       return $this; 
    }
    
    /**
      *   Fetches the parent selector
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws EngineException
      */
    public function parentSelector()
    {
        if($this->head instanceof SchemaNode) {
            throw new EngineException('Has no parent selector');
        }
        
        if($this->head instanceof TableNode) {
            throw new EngineException('Has no parent selector');
        }
        
        if($this->head instanceof ColumnNode) {
            throw new EngineException('Has no parent selector');
        }
        
        do {
            # break loop at first column node
            if($this->head instanceof ColumnNode) {
                break;
            }
            
            $this->head = $this->head->getParent();
        }
        while(!$this->head instanceof SelectorNode);
        
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
       # iterate up to schema
       if($this->head instanceof SchemaNode) {
            $this->parentSchema();
       }
       
       # find the table
       foreach($this->head->getChildren() as $table) {
         if($table->getId() === $name) {
            $this->head = $table;
            break;
         }
        
       }
       
       if(!$this->head instanceof TableNode) {
            return null;
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
       # iterate up to schema
       if($this->head instanceof TableNode) {
            $this->parentTable();
       }
       
       # find the table
       foreach($this->head->getChildren() as $column) {
         if($column->getId()=== $name) {
            $this->head = $column;
            break;
         }
        
       }
       
       if(!$this->head instanceof ColumnNode) {
            return null;
       }
        
       return $this;
    }
    
     /**
      *   Fetches the type using name
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws EngineException
      */
    public function child($name)
    {
       # iterate up to schema
       if($this->head instanceof ColumnNode) {
            $this->parentColumn();
       }
       
       # find the table
       foreach($this->head->getChildren() as $columnChild) {
         if($columnChild->getId() === $name) {
            $this->head = $columnChild;
            break;
         }
        
       }
       
       if(!$this->head instanceof TypeNode) {
            return null;
       }
        
       return $this;
    }
    
}
/* End of File */