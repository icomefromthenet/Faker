<?php
namespace Faker\Components\Faker\Composite;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Components\Faker\CacheInterface,
    Faker\Components\Faker\GeneratorCache,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

/*
 * class ForeignKey
 *
 * @version 1.0.2
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 */

class ForeignKey extends BaseComposite implements CacheInterface
{
     /**
      *  @var CompositeInterface 
      */
    protected $parent_type;
    
    /**
      *  @var CompositeInterface[] 
      */
    protected $child_types = array();
    
    /**
      *  @var string the id of the component 
      */
    protected $id;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  @var GeneratorCache the cache which old all values
      */
    protected $cache;
    
    /**
      *  @var boolean true to use cache class 
      */
    protected $use_cache = true;
    
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent 
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event, $options = array())
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        $this->options = $options;
        $this->use_cache = true;
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        # rewind on the first row
        if($rows === 1) {
            $this->cache->rewind();
        }
        
        # fetch the current value
        $value = $this->cache->current();
        
        # iterate to next value
        $this->cache->next();
        
        return $value;
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
        return $this->child_types;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function addChild(CompositeInterface $child)
    {
        return array_push($this->child_types,$child);
    }
    
    
    /**
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher()
    {
        return $this->event;
    }
    
    
    /**
      *  Convert the column into xml representation
      *
      *  @return string
      *  @access public
      */
    public function toXml()
    {
        $str  = '<foreign-key ';
        $str .= 'name="' . $this->getOption('foreignTable') . '.' .$this->getOption('foreignColumn').'" ';
        $str .= 'foreignColumn="'.$this->getOption('foreignColumn').'" ';
        $str .= 'foreignTable="'.$this->getOption('foreignTable').'"';
        $str.=  '>'.PHP_EOL;
    
        foreach($this->getChildren() as $child) {
            $str .= $child->toXml();
        }
    
        $str .= '</foreign-key>'. PHP_EOL;
      
        return $str;
        
    }
    
    //  -------------------------------------------------------------------------

    public function validate()
    {
        # ask children to validate themselves
        foreach($this->getChildren() as $child) {
          $child->validate(); 
        }
        
        # check if cache has been set
        if(!$this->cache instanceof GeneratorCache) {
            throw new FakerException('Foreign-key requires a cache to be set');
        }

        return true;  
    }

    //  -------------------------------------------------------------------------
    # Option Interface
    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');

        $rootNode
            ->children()
                ->scalarNode('foreignTable')
                    ->isRequired()
                    ->setInfo('The exact name of the foreign table')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('foreignColumn')
                    ->isRequired()
                    ->setInfo('Name of the Foreign Column')
                    ->cannotBeEmpty()
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
    //  ------------------------------------------------------------------------- 
    # CacheInterface
    
    /**
      *  @inheritdoc
      */
    public function setGeneratorCache(GeneratorCache $cache)
    {
        $this->cache = $cache;
        return $this;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getGeneratorCache()
    {
        return $this->cache;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function setUseCache($bool)
    {
        $this->use_cache = $bool;
        
        return $this;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getUseCache()
    {
        return $this->use_cache;
    }
    
    //------------------------------------------------------------------
    
}
/* End of File */