<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Utilities,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\TypeInterface,
    Faker\Components\Faker\TypeConfigInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Type implements CompositeInterface, TypeConfigInterface 
{
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var string id of the component  
      */
    protected $id;
    
    /**
      *  @var CompositeInterface 
      */
    protected $parent_type;

    /**
      * @var  Faker\Components\Faker\Utilities 
      */
    protected $utilities;
    
    /**
      *  @ options 
      */
    protected $options = array();
    
    //  -------------------------------------------------------------------------
    
     /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the type name
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      *  @param Utilities $util
      */
    public function __construct($id , CompositeInterface $parent, EventDispatcherInterface $event, Utilities $util)
    {
        $this->id = $id;
        $this->event = $event;
        $this->utilities = $util;
        
        if($parent !== null) {
            $this->setParent($parent);
        }
    }
   
   
    //  -------------------------------------------------------------------------
	
    public function generate($rows,$values = array())
    {
        throw new FakerException('not implemented');        
    }
    
    //  -------------------------------------------------------------------------
     
    /**
      *  @inheritdoc 
      */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
      * @inheritdoc
      */
    public function getParent()
    {
        return $this->parent_type;
    }

    /**
      * @inheritdoc  
      */
    public function setParent(CompositeInterface $parent)
    {
        $this->parent_type = $parent;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getChildren()
    {
        throw new FakerException('Not Implemented');
    }
    
    
    /**
      *  @inheritdoc
      */
    public function addChild(CompositeInterface $child)
    {
        throw new FakerException('Not Implemented');
    }

    //  -------------------------------------------------------------------------

    public function getEventDispatcher()
    {
          return $this->event;
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function toXml()
    {
        throw new FakerException('not implemented');
    }
    
    //  -------------------------------------------------------------------------
     
    public function validate()
    {
	return false;        
    }

    //  ------------------------------------------------------------------------	# Block Name

    public function merge($config)
    {
	throw new FakerException('not implemented');
    }

    //  -------------------------------------------------------------------------
    
    public function getUtilities()
    {
	return $this->utilities;
    }
    
    public function setUtilities(Utilities $util)
    {
	$this->utilities = $util;
    }
 
    //  -------------------------------------------------------------------------

    public function setOption($name,$option)
    {
	$this->options[$name] = $option;
    }
    
    public function getOption($name)
    {
	return $this->options[$name];
    }
    
    //  -------------------------------------------------------------------------

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
	
	$rootNode->children()
	      ->scalarNode('locale')
                    ->treatNullLike('en')
                    ->defaultValue('en')
                    ->setInfo('The Default Local for this schema')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Faker\Exception('Schema::Locale not in valid list');
                        })
                    ->end()
                ->end()
	->end();
	
	$this->getConfigExtension($rootNode);
	
	return $treeBuilder;
    }
    
    //  -------------------------------------------------------------------------
    
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
	throw new FakerException('not implemented');
    }
    
}
/* End of File */