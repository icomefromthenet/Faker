<?php
namespace Faker\Components\Faker\Composite;

use Faker\Components\Faker\OptionInterface,
    Faker\Components\Faker\BaseNode,
    Faker\Components\Faker\Visitor\GeneratorInjectorVisitor,
    Faker\Components\Faker\Visitor\ColumnCacheInjectorVisitor,
    Faker\Components\Faker\Visitor\ForeignCacheInjectorVisitor,
    Faker\Components\Faker\Visitor\MapBuilderVisitor,
    Faker\Components\Faker\Visitor\RefCheckVisitor,
    Faker\Components\Faker\Visitor\BaseVisitor,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Faker\Components\Faker\Exception as FakerException;

/*
 * class BaseComposite the basic class for composite nodes
 */

abstract class BaseComposite extends BaseNode implements OptionInterface, CompositeInterface
{
    
    /**
      *  @var string[] array of options 
      */
    protected $options;
    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');

        return $treeBuilder;
    }
    
    /**
      *  @inheritdoc 
      */
    public function setOption($name,$value)
    {
        $this->options[$name]= $value;
    }
    
    /**
      *  @inheritdoc 
      */
    public function getOption($name)
    {
        return $this->options[$name];
    }
    
    /**
      *  Check if the option is set
      *
      *  @param string $name the option name
      *  @return boolean true if set
      *  @access public
      */
    public function hasOption($name)
    {
         return isset($this->options[$name]);
    }
   
    //------------------------------------------------------------------
    # Composite Interface (overriden in child interfaces)
    
    public function getParent()
    {
        throw new FakerException('Not Implemented');    
    }

    public function setParent(CompositeInterface $parent)
    {
        throw new FakerException('Not Implemented');    
    }
    
    public function getChildren()
    {
        throw new FakerException('Not Implemented');
    }
    
    public function addChild(CompositeInterface $child)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function getEventDispatcher()
    {
        throw new FakerException('Not Implemented');
    }
    
    public function toXml()
    {
        throw new FakerException('Not Implemented');
    }
    
    public function validate()
    {
        throw new FakerException('Not Implemented');
    }
    
    /**
      *  Merge config with the symfony config tree builder
      *
      *  @param string[] array config values
      *  @return void
      *  @throws Faker\Components\Faker\Exception  when validation fails
      */
    public function merge()
    {
        try {
            $processor = new Processor();
            $options = $processor->processConfiguration($this, array('config' => $this->options));
            
            foreach($options as $name => $value) {
                $this->setOption($name,$value);
            }
            
            # call merge on children
            foreach($this->getChildren() as $child) {
                $child->merge();
            }
            
        }catch(InvalidConfigurationException $e) {
            
            throw new FakerException($e->getMessage());
        }
    }
    
    //------------------------------------------------------------------
    # Base Node
    
    /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BaseVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BaseVisitor $visitor)
    {
       
       if($visitor instanceof ColumnCacheInjectorVisitor) {
            $visitor->visitCacheInjector($this);
       }
       
       if($visitor instanceof ForeignCacheInjectorVisitor) {
            $visitor->visitCacheInjector($this);
       }
       
       if($visitor instanceof MapBuilderVisitor) {
            $visitor->visitMapBuilder($this);
       }
       
       if($visitor instanceof RefCheckVisitor) {
            $visitor->visitRefCheck($this);
       }
       
       if($visitor instanceof GeneratorInjectorVisitor) {
            $visitor->visitGeneratorInjector($this);
       }
       
       # send visitor to the children.
       
       foreach($this->getChildren() as $child) {
            $child->acceptVisitor($visitor);
       }
       
       return $visitor;
       
    }
    
    //------------------------------------------------------------------
}
/* End of File */