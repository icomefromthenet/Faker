<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

use Faker\Components\Engine\Common\Builder\AbstractDefinition;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Builder\DefaultTypeDefinition;


/**
  *  Builder to construct a single field
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FieldBuilder implements NodeInterface
{
    
    protected $name;
    protected $parent;
    
    protected $event;
    protected $connection;
    protected $generator;
    protected $locale;
    protected $utilities;
    
    /**
      *  @var Faker\Components\Engine\Common\TypeRepository; 
      */
    protected $repo;
    
    /**
      *  Class Constructor 
      */
    public function __construct($name, TypeRepository $repo, EventDispatcherInterface $event, Utilities $util, GeneratorInterface $generator, LocaleInterface $locale, Connection $conn)
    {
        $this->name       = $name;
        $this->repo       = $repo;
        $this->event      = $event;
        $this->connection = $conn;
        $this->generator  = $generator;
        $this->utilities  = $util;
        $this->locale     = $locale;  
    }
    
    //------------------------------------------------------------------
    # NodeInterface
    
    /**
      *  Fetch the generator composite node managed by this builder node
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode($id,CompositeInterface $parent)
    {
        
    }
    
    
    /**
    * Sets the parent node.
    *
    * @param ParentNodeInterface $parent The parent
    */
    public function setParent(NodeInterface $parent)
    {
        $this->parent = $node;
    }
    
    
    /**
    * Returns the parent node.
    *
    * @return NodeInterface The builder of the parent node
    */
    public function end()
    {
        return $this->parent;
    }
    
    
    //------------------------------------------------------------------
    # Comment
    
    
    protected function find($alias)
    {
        if(($resolvedAlias = $this->repo->find($alias)) === null) {
            throw new EngineException("$alias not found in type repository unable to create");
        }
        
        
        # check if class is not a definition 
        if(!$resolvedAlias instanceof AbstractDefinition) {
            # not so need to wrap in default definition
            # it may be is a custom type added by user
            $field = new DefaultTypeDefinition();
            $field->className($resolvedAlias);
        } else {
            $field = new $resolvedAlias();
        }
        
        $field->setParent($this);
        $field->generator($this->generator);
        $field->utilities($this->utilities);
        $field->database($this->connection);
        $field->locale($this->locale);
        
        return $field;
    }
    
    
    public function fieldAlphaNumeric()
    {
        return $this->find('alphanumeric');
    }
    
    
    public function fieldAutoIncrement()
    {
        
    }
    
    
    public function fieldBoolean()
    {
        
    }
    
    public function fieldCity()
    {
        
    }
    
    public function fieldConstant()
    {
        
    }
    
    public function fieldcountry()
    {
        
    }
    
    public function fieldDate()
    {
        
    }
    
    
    public function fieldEmail()
    {
        
    }
    
    
    public function fieldPeopleName()
    {
        
    }
    
    public function fieldNull()
    {
        
    }
    
    
    public function fieldNumeric()
    {
        
    }
    
    public function fieldRange()
    {
        
    }
    
    
    public function fieldRegex()
    {
        
    }
    
    
    public function fieldTemplate()
    {
        
    }
    
    
    public function fieldText()
    {
        
    }
    
    
    public function fieldUniqueNumber()
    {
        
    }
    
    
    public function fieldUniqueString()
    {
        
    }
    
    
    public function fieldCustom($alias)
    {
        return $this->find($alias);
    }
    
   
       
    
}
/* End of File */