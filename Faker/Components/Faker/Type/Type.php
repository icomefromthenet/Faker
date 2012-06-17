<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Utilities,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\TypeInterface,
    Faker\Components\Faker\BaseNode,
    Faker\Components\Faker\Visitor\CacheInjectorVisitor,
    Faker\Components\Faker\Visitor\MapBuilderVisitor,
    Faker\Components\Faker\Visitor\RefCheckVisitor,
    Faker\Components\Faker\Visitor\BaseVisitor,
    Faker\Components\Faker\TypeConfigInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException,
    Faker\Components\Faker\OptionInterface;

class Type extends BaseNode implements CompositeInterface, TypeConfigInterface
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
       return array();
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

    //  ------------------------------------------------------------------------
    

    public function merge()
    {
	try {
            
            $processor = new Processor();
            $options = $processor->processConfiguration($this, array('config' => $this->options));
	    foreach($options as $name => $value ) {
		$this->setOption($name,$value);
	    }
	
	    foreach($this->getChildren() as $child) {
		$child->merge();
	    }
            
        }catch(InvalidConfigurationException $e) {
            
            throw new FakerException($e->getMessage());
        }
	
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
    # Option Interface
  
    public function setOption($name,$option)
    {
	$this->options[$name] = $option;
    }
    
    public function getOption($name)
    {
	return $this->options[$name];
    }
    
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
    
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
	throw new FakerException('not implemented');
    }
    
    //------------------------------------------------------------------
    # Base Node Interface
    
    /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BaseVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BaseVisitor $visitor)
    {
        
	# no processing yet needed.
	
	
	return $visitor;
    }
    
    //------------------------------------------------------------------
    
    
    
}
/* End of File */