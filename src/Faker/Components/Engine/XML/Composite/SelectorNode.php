<?php
namespace Faker\Components\Engine\XML\Composite;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use PHPStats\Generator\GeneratorInterface;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Composite\SerializationInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Selector\AlternateSelector;
use Faker\Components\Engine\Common\Selector\PickSelector;
use Faker\Components\Engine\Common\Selector\RandomSelector;
use Faker\Components\Engine\Common\Selector\SwapSelector;


use Faker\Components\Engine\DB\Composite\SelectorNode as BaseNode;

/**
  *  Node that implements SerializationInterface and VisitorInterface and OptionInterface
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorNode extends BaseNode implements OptionInterface, SerializationInterface, VisitorInterface, CompositeInterface, TypeInterface
{
    
    /**
     *  @var array[string] the options
    */
    protected $options;
    
    /**
     *  @var Faker\Locale\LocaleInterface
    */
    protected $locale;
    
    /**
     *  @var PHPStats\Generator\GeneratorInterface
    */
    protected $generator;
    
    /**
     *  @var Faker\Components\Engine\Common\Utilities
    */
    protected $utilities;
    
  
    protected $customOptions = array('name' ,'locale','randomGenerator','generatorSeed');
  
    
    //-------------------------------------------------------
    # CompositeInterface
    
    public function validate()
    {
        
        # validate the options passed into config
        try {
            $this->getInternal()->validate();
        
            $processor = new Processor();
            $options = $processor->processConfiguration($this, array('config' => $this->options));
            
            # reset the options as they be modified by the config processor
            foreach($options as $name => $value) {
                $this->setOption($name,$value);
            }
            
        }catch(InvalidConfigurationException $e) {
            throw new CompositeException($this,$e->getMessage(),0,$e);
        }catch(EngineException $e) {
            throw new CompositeException($this,$e->getMessage(),0,$e);
        }
        
        # validate the child nodes in composite
        $children = $this->getChildren();
        
        foreach($children as $child) {
            $child->validate();
        }
        
        return true;
    }
    
    
    //-------------------------------------------------------
    # OptionsInterface
    
    public function setOption($name,$value)
    {
        if(in_array($this->customOptions) === true) {
            $this->options[$name]= $value;    
        }
        else {
            $this->getInternal()->setOption($name,$value);
        }
    }
    
    public function getOption($name)
    {
        if(in_array($this->customOptions) === true) {
            $option = $this->options[$name];
        }
        else {
            $option = $this->getInternal()->getOption($name);
        }
        
        return $option;
    }
    
    public function hasOption($name)
    {
        if(in_array($this->customOptions) === true) {
            $set = isset($this->options[$name]);
        }
        else {
            $set = $this->getInternal()->hasOption($name);
        }
        
        return $set;
    }
    
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');

        $rootNode
            ->children()
                ->scalarNode('name')
                    ->isRequired()
                    ->info('The name of the node')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('Type::Name must be a string');
                        })
                    ->end()
                ->end()
                ->scalarNode('locale')
                    ->treatNullLike('en')
                    ->defaultValue('en')
                    ->info('The Default Locale for this node')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('Type::Locale not in valid list');
                        })
                    ->end()
                ->end()
                ->scalarNode('randomGenerator')
                    ->info('Type of random number generator to use')
                    ->validate()
                        ->ifTrue(function($v){
                            return empty($v) or !is_string($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('randomGenerator must not be empty or string');
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
                            throw new InvalidConfigurationException('generatorSeed must be an integer');
                        })
                    ->end()
                ->end()
            ->end()
        ->end();
            
        return $treeBuilder;
    }
    
    
    //-------------------------------------------------------
    # SerializationInterface
    
    public function toXml()
    {
       return '';
    }
    
    
    //-------------------------------------------------------
    # VisitorInterface
    
    public function acceptVisitor(BasicVisitor $visitor)
    {
         # execute visitors that apply to this node
        $visitor->visitDirectedGraphBuilder($this);
        
        $visitor->visitGeneratorInjector($this);
        $visitor->visitLocaleInjector($this);
        
        # execute accept on children
        $children = $this->getChildren();
        
        foreach($children as $child) {
            if($child instanceof VisitorInterface) {
                $child->acceptVisitor($visitor);
            }
        }
        
        return $visitor;
    }
    
    //-------------------------------------------------------
    # TypeInterface
    
    
    public function getUtilities()
    {
        return $this->utilities;
    }
    
    public function setUtilities(Utilities $util)
    {
        $this->utilities = $util;
        
        return $this;
    }
    
    public function getGenerator()
    {
        return $this->generator;
    }
    
    public function setGenerator(GeneratorInterface $generator)
    {
        $this->generator = $generator;
        
        return $this;
    }

    public function setLocale(LocaleInterface $locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    public function getLocale()
    {
        return $this->locale;
    }
    
    //-------------------------------------------------------
    
}
/* End of File */