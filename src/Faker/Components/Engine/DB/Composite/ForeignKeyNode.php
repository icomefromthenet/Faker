<?php
namespace Faker\Components\Engine\DB\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Composite\ForeignKeyNode as BaseForeignKeyNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;


/**
  *  Foreign Node implements GeneratorInterface, VisitorInterface, OptionInterface on top of the base node
  *  from common namespace.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ForeignKeyNode extends BaseForeignKeyNode implements GeneratorInterface, VisitorInterface, OptionInterface
{
    
    /**
      *  @var array[mixed] 
      */
    protected $options;
    
    /**
      *  @var  Faker\Components\Engine\Common\GeneratorCache
      */
    protected $resultCache;
    
    
     //------------------------------------------------------------------
    # GeneratorInterface
    
    public function generate($rows,$values = array())
    {
        $cache = $this->getResultCache();
        
        # return null if cache not set 
        if(!$cache instanceof GeneratorCache ) {
            return null;
        }
            
        # rewind on the first row
        if($rows === 1) {
            $cache->rewind();
        }
        
        # fetch the current value
        $value = $cache->current();
        
        # iterate to next value
        $cache->next();
        
        return $value;
    }
    
    public function setResultCache(GeneratorCache $cache)
    {
        $this->resultCache = $cache;
    }
    

    public function getResultCache()
    {
        return $this->resultCache;
    }
    
    //------------------------------------------------------------------
    # VisitorInterface
    /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BasicVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BasicVisitor $visitor)
    {
        # execute visitors that apply to this node
        
        # execute accept on children
        $children = $this->getChildren();
        
        foreach($children as $child) {
            if($child instanceof VisitorInterface) {
                $child->acceptVisitor($visitor);
            }
        }
        
        return $visitor;
    }
    
    //------------------------------------------------------------------
    # OptionInterface
    
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
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
}
/* End of File */