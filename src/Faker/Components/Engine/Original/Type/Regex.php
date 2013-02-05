<?php
namespace Faker\Components\Engine\Original\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    ReverseRegex\Lexer,
    ReverseRegex\Parser,
    ReverseRegex\Exception as ReverseRegexException,
    ReverseRegex\Generator\Scope;
    
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
    public function generate($rows,$values = array())
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
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition 
     */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode
            ->children()
                ->scalarNode(self::FORMAT)
                    ->info('Regex to use')
                    ->example('[a-z]{1,6}')
                ->end()
            ->end();
            
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        try {
       
            $lexer  = new Lexer($this->getOption(self::FORMAT));
            $parser = new Parser($lexer, new Scope(),new Scope());
            $this->result = $parser->parse()->getResult();
        
        } catch(ReverseRegexException $e) {
            throw new FakerException($e->getMessage());
        }
        
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */