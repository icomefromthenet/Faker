<?php
namespace Faker\Components\Engine\Original\Distribution;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\BaseNode,
    Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Visitor\BaseVisitor,
    PHPStats\Generator\GeneratorInterface,
    PHPStats\PDistribution\ProbabilityDistributionInterface,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Distribution extends BaseNode implements CompositeInterface, OptionInterface, DistributionInterface
{
    
    protected $parent;
    
    protected $options;
    
    protected $generator;
    
    protected $distribution;
    
    //  ----------------------------------------------------------------------------
    # CompositeInterface Implementation
    
    /**
      *  Fetches the parent in this type composite
      *
      *  @return Faker\Components\Engine\Original\Composite\CompositeInterface
      *  @access public
      */
    public function getParent()
    {
        return $this->parent;
    }

    /**
      *  Sets the parent of this type composite
      *
      *  @access public
      *  @param Faker\Components\Engine\Original\Composite\CompositeInterface $parent;
      */
    public function setParent(CompositeInterface $parent)
    {
        $this->parent = $parent;
    }
    
    
    /**
      *   Fetches the children of this type composite
      *
      *   @access public
      *   @return Faker\Components\Engine\Original\Composite\CompositeInterface[] 
      */
    public function getChildren()
    {
       throw new FakerException('Not Implemented'); 
    }
    
    
    /**
      *  Add's a child to this type composite
      *
      *  @param Faker\Components\Engine\Original\Composite\CompositeInterface $child
      */
    public function addChild(CompositeInterface $child)
    {
        throw new FakerException('Not Implemented');
    }
    
    
    /**
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher()
    {
        throw new FakerException('Not Implemented');
    }
    
    /**
      *  Convert the composite to xml
      *
      *  @return string of xml
      */
    public function toXml()
    {
        throw new FakerException('Not Implemented');
    }
    
    /**
      *  Checks that each composite is in valid state
      *
      *  @return boolean
      *  @access public
      *  @throws Faker\Components\Engine\Original\Exception
      */
    public function validate()
    {
        
    }
    
    /**
      *  Merge config with the current node
      *
      *  @return void
      *  @access public
      *  @throws Faker\Components\Engine\Original\Exception when config error occurs
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
    
    
    //  ----------------------------------------------------------------------------
    # BaseNode Implementation
    
     /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BaseVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BaseVisitor $visitor)
    {
        return $visitor;
    }
    
    
    //  ----------------------------------------------------------------------------
    # Options Interface
    
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
                 ->scalarNode('name')
                    ->isRequired()
                    ->info('The Name of the Column')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Engine\Original\Exception('Column::Name must be a string');
                        })
                    ->end()
                    ->scalarNode('randomGenerator')
                    ->info('Type of random number generator to use')
                    ->validate()
                        ->ifTrue(function($v){
                            return empty($v) or !is_string($v);
                        })
                        ->then(function($v){
                            throw new FakerException('randomGenerator must not be empty or string');
                        })
                    ->end()
                ->end()
                ->scalarNode('generatorSeed')
                    ->info('Seed value to use in the generator')
                    ->validate()
                        ->ifTrue(function($v){
                            return ! is_integer($v);
                        })
                        ->then(function($v){
                            throw new FakerException('generatorSeed must be an integer');
                        })
                    ->end()
                ->end()
            ->end();
        
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
    
    //  ----------------------------------------------------------------------------
    # Distribution Interface
    
    
     /**
      *  Fetch the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function getGenerator()
    {
        return $this->generator;
    }
    
    /**
      *  Set the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function setGenerator(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }
    
    
    /**
      *  Set the assigned distribution
      *
      *  @param HPStats\PDistribution\ProbabilityDistributionInterface
      *  @access public
      */
    public function setDistribution(ProbabilityDistributionInterface $dist)
    {
        $this->distribution = $dist;
    }
    
    /**
      *  Return the assigned distribution
      *
      *  @return HPStats\PDistribution\ProbabilityDistributionInterface
      *  @access public
      */
    public function getDistribution()
    {
        return $this->distribution;
    }
    
}
/* End of File */