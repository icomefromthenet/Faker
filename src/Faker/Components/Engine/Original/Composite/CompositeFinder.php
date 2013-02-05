<?php
namespace Faker\Components\Engine\Original\Composite;

use Faker\Components\Engine\Original\Exception as BaseException;

/**
  *  Finder class for composite queries
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  */
class CompositeFinder
{
    /**
      *  @var Faker\Components\Engine\Original\CompositeInterface 
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
      *   @throws FakerException
      */
    public function parentTable()
    {
        if($this->head instanceof Schema) {
            throw new FakerException('Has no parent table');
        }
        
        do {
            $this->head = $this->head->getParent();
        }
        while(!$this->head instanceof Table);
        
        return $this;
    }
    
    /**
      *   Fetches the parent column
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws FakerException
      */
    public function parentColumn()
    {
        if($this->head instanceof Schema) {
            throw new FakerException('Has no parent column');
        }
        
        if($this->head instanceof Table) {
            throw new FakerException('Has no parent column');
        }
        
        do {
            $this->head = $this->head->getParent();
        }
        while(!$this->head instanceof Column);
        
        return $this;
    }
    
     /**
      *   Fetches the parent schema
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws FakerException
      */
    public function parentSchema()
    {
       if(!$this->head instanceof Schema) {
            do {
                $this->head = $this->head->getParent();
            }
            while(!$this->head instanceof Schema);
       }
        
       return $this; 
    }
    
    /**
      *   Fetches the parent selector
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws FakerException
      */
    public function parentSelector()
    {
        if($this->head instanceof Schema) {
            throw new FakerException('Has no parent selector');
        }
        
        if($this->head instanceof Table) {
            throw new FakerException('Has no parent selector');
        }
        
        if($this->head instanceof Column) {
            throw new FakerException('Has no parent selector');
        }
        
        do {
            if($this->head instanceof Column) {
                return null;    
            }
            
            $this->head = $this->head->getParent();
        }
        while(!$this->head instanceof SelectorInterface);
        
        return $this;
    }
    
    /**
      *   Fetches the table using name
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws FakerException
      */
    public function table($name)
    {
       # iterate up to schema
       if($this->head instanceof Schema) {
            $this->parentSchema();
       }
       
       # find the table
       foreach($this->head->getChildren() as $table) {
         if($table->getOption('name') === $name) {
            $this->head = $table;
            break;
         }
        
       }
       
       if(!$this->head instanceof Table) {
            return null;
       }

       return $this; 
    }
    
     /**
      *   Fetches the table column using name
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws FakerException
      */
    public function column($name)
    {
       # iterate up to schema
       if($this->head instanceof Table) {
            $this->parentTable();
       }
       
       # find the table
       foreach($this->head->getChildren() as $table) {
         if($table->getOption('name') === $name) {
            $this->head = $table;
            break;
         }
        
       }
       
       if(!$this->head instanceof Column) {
            return null;
       }
        
       return $this;
    }
    
     /**
      *   Fetches the type using name
      *
      *   @return CompositeFinder
      *   @access public
      *   @throws FakerException
      */
    public function type($name)
    {
       # iterate up to schema
       if($this->head instanceof Column) {
            $this->parentColumn();
       }
       
       # find the table
       foreach($this->head->getChildren() as $table) {
         if($table->getOption('name') === $name) {
            $this->head = $table;
            break;
         }
        
       }
       
       if(!$this->head instanceof Type) {
            return null;
       }
        
       return $this;
    }
    
}
/* End of File */