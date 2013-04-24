<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;    

use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Exception as ReverseRegexException;
use ReverseRegex\Generator\Scope;

/**
 * Use a Regex to generate a string
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */    
class Regex extends Type
{

    const FORMAT     = 'format';
    
    /**
      *  @var ReverseRegex\ContextInterface instance of the generator object 
      */
    protected $result;

    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,&$values = array())
    {
        $generator  = $this->getGenerator();
        $str        = '';
        $this->result->generate($str,$generator);
        
        return  $str;
    }
    
    
   
    //  -------------------------------------------------------------------------
    
    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        
        $rootNode
            ->children()
                ->scalarNode(self::FORMAT)
                    ->info('Regex to use')
                    ->example('[a-z]{1,6}')
                ->end()
            ->end();
        
        return $treeBuilder;    
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        parent::validate();
        
        try {
       
            $lexer  = new Lexer($this->getOption(self::FORMAT));
            $parser = new Parser($lexer, new Scope(),new Scope());
            $this->result = $parser->parse()->getResult();
        
        } catch(ReverseRegexException $e) {
            throw new EngineException($e->getMessage());
        }
        
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */