<?php
namespace Faker\Components\Engine\XML\Composite;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use PHPStats\Generator\GeneratorInterface;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Composite\SerializationInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\Composite\CompositeException;

use Faker\Components\Engine\DB\Composite\ForeignKeyNode as BaseNode;

/**
  *  Node that implements SerializationInterface and VisitorInterface and OptionInterface
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ForeignKeyNode extends BaseNode implements OptionInterface, SerializationInterface, VisitorInterface, CompositeInterface, TypeInterface
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
    
    
    //-------------------------------------------------------
    # CompositeInterface
    
    public function validate()
    {
        $children = $this->getChildren();
        
        # run the parent validation function
        parent::validate();
        
        # validate the child nodes in composite
        foreach($children as $child) {
            $child->validate();
        }
        
        return true;
    }
    
    
    //-------------------------------------------------------
    # OptionsInterface
    
    public function setOption($name,$value)
    {
        $this->options[$name]= $value;
    }
    
    public function getOption($name)
    {
        return $this->options[$name];
    }
    
    public function hasOption($name)
    {
         return isset($this->options[$name]);
    }
    
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');

        $rootNode
            ->children()
              ->scalarNode('foreignTable')
                    ->isRequired()
                    ->info('The exact name of the foreign table')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('foreignColumn')
                    ->isRequired()
                    ->info('Name of the Foreign Column')
                    ->cannotBeEmpty()
                ->end()
                ->booleanNode('silent')
                    ->defaultFalse()
                    ->info('Use option to supress value generation but keep Foreign Key Reference Checks and Toplogical Reorder')  
                ->end()
                ->scalarNode('name')
                    ->isRequired()
                    ->info('The Name of the Foreign Key')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('ForeignKey::Name must be a string');
                        })
                    ->end()
                ->end()
                ->scalarNode('locale')
                    ->treatNullLike('en')
                    ->defaultValue('en')
                    ->info('The Default Locale for this type')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('ForeignKey::Locale not in valid list');
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
        $str  = '<foreign-key ';
        $str .= 'name="' . $this->getOption('foreignTable') . '.' .$this->getOption('foreignColumn').'" ';
        $str .= 'foreignColumn="'.$this->getOption('foreignColumn').'" ';
        $str .= 'foreignTable="'.$this->getOption('foreignTable').'"';
        //$str .= 'useCache="'.var_export($this->getOption('useCache')).'"';
        $str.=  '>'.PHP_EOL;
    
        foreach($this->getChildren() as $child) {
            $str .= $child->toXml();
        }
    
        $str .= '</foreign-key>'. PHP_EOL;
      
        return $str;
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