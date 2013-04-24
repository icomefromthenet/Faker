<?php
namespace Faker\Components\Engine\Original\Composite;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Common\Formatter\FormatEvents,
    Faker\Components\Engine\Original\Formatter\GenerateEvent,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Faker\Components\Engine\Original\Composite\CompositeInterface;


class Table extends BaseComposite
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
      *  @var number of rows to generate 
      */
    protected $rows;
    
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      *  @param integer the number of rows to generate
      *  @param mixed[] array of extra options
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event,$rows,$options = array())
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        $this->options = $options;
        
        # set the rows to generate
        
        if(is_integer($rows) === false) {
            throw new FakerException('Table Type must have rows to generate as an integer');
        }
        
        $this->rows = $rows;
        
    }
   
    /**
      *  @inheritdoc 
      */
    public function generate($rows,&$values = array())
    {
        # dispatch the start table event
       
        $this->event->dispatch(
                FormatEvents::onTableStart,
                new GenerateEvent($this,$values,$this->getOption('name'))
        );
   
   
        do {
                
                # reset values for next row run.
                
                $values = array();
                
                # dispatch the row start event
            
                $this->event->dispatch(
                    FormatEvents::onRowStart,
                    new GenerateEvent($this,$values,$this->getOption('name'))
                );

                # send the generate event to the columns
       
                foreach($this->child_types as $type) {
                    $values = $type->generate($rows,$values);            
                }
        
                
                # dispatch the row stop event
                
                $this->event->dispatch(
                    FormatEvents::onRowEnd,
                    new GenerateEvent($this,$values,$this->getOption('name'))
                );

                    
                # increment the rows needed by datatypes. 
                $rows = $rows +1;
        }
        while($rows <= $this->rows);
        
        
        # dispatch the stop table event
        
        $this->event->dispatch(
                    FormatEvents::onTableEnd,
                    new GenerateEvent($this,$values,$this->getOption('name'))
        );
        
        return null;
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
      *  @inheritdoc
      */
    public function getEventDispatcher()
    {
          return $this->event;
    }
    
    
    public function toXml()
    {
        $str = sprintf('<table name="%s" generate="0">',$this->getOption('name')). PHP_EOL;
     
        foreach($this->child_types as $child) {
               $str .= $child->toXml();     
        }
     
        $str .= '</table>' . PHP_EOL;
      
        return $str;
    }
    

    //  -------------------------------------------------------------------------

    public function validate()
    {
        # ask children to validate themselves
        
        foreach($this->getChildren() as $child) {
        
          $child->validate(); 
        }
        
        # check that children have been added
        
        if(count($this->getChildren()) === 0) {
          throw new FakerException('Table must have at least 1 column');
        }

        return true;  
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Return the number of rows this table will generate
      *
      *  @access public
      *  @return integer the rows to generate
      */
    public function getToGenerate()
    {
        return $this->rows;
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
        $rootNode = $treeBuilder->root('config');

        $rootNode
            ->children()
                  ->scalarNode('name')
                    ->isRequired()
                    ->info('The Name of the Table')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Engine\Original\Exception('Table::Name must be a string');
                        })
                    ->end()
                ->end()
                ->scalarNode('locale')
                    ->treatNullLike('en')
                    ->defaultValue('en')
                    ->info('The Default Local for this schema')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Engine\Original\Exception('Table::Locale not in valid list');
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
                            throw new FakerException('Table::randomGenerator must not be empty or string');
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
                            throw new FakerException('Table::generatorSeed must be an integer');
                        })
                    ->end()
                ->end()
                ->scalarNode('generate')
                    ->info('The number of rows to generate')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_integer($v);
                        })
                        ->then(function($v){
                            throw new FakerException('Table::Generate must be and integer');
                        })
                    ->end()
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
}
/* End of File */