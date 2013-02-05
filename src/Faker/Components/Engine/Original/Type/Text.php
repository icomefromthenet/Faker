<?php
namespace Faker\Components\Engine\Original\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Text extends Type
{


    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $parag      = $this->getOption('paragraphs');
        $min_lines  = $this->getOption('minlines');
        $max_lines  = $this->getOption('maxlines');
        $return     = '';
     
        # generate the text
        for($i = $parag; $i > 0; $i--) {
            $return .=  $this->generateRandomText($min_lines,$max_lines).PHP_EOL;
        }
        
        return $return;
    }
    
    /**
     * Generates a string of lorem ipsum words.
     *
     * @param string[] $words the lines to pick from 
     * @param integer $min the minimum # of words to return OR the total number
     * @param integer $max the max # of words to return (or null for "fixed" type)
     * @param GeneratorInterface $random
     */
    public function generateRandomText($min, $max)
    {
        $words = $this->locale->getFillerText();
        
        # how many lines do we need from range
        $num_lines  = ceil($this->generator->generate($min, $max));
        
        # set line_count for 0 based array
        if(($line_count = count($words)) > 1) {
            $line_count = $line_count -1;
        }
    
        # fetch random rows for each lines;
        $r = array();
        for ($i = 1; $i <= $num_lines; $i++) {
            $lin = ceil($this->generator->generate(0,$line_count));
            $r[] = $words[$lin];
        }
        
        # join together the selected rows
        return join(" ", $r);
           
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
                ->scalarNode('paragraphs')
                    ->defaultValue(4)
                    ->info('Text format to use')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_int($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Engine\Original\Exception('Numeric::Paragraphs must be and integer');
                        })
                    ->end()
                ->end()
                ->scalarNode('maxlines')
                    ->defaultValue(200)
                    ->info('Maxium number of line per paragraph')
                    ->example('5 | 10 | ...')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_integer($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Engine\Original\Exception('Numeric::maxlines must be and integer');
                        })
                    ->end()
                ->end()
                ->scalarNode('minlines')
                    ->defaultValue(5)
                    ->info('Minimum number of lines per paragraph')
                    ->example('20 | 100 | ..')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_integer($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Engine\Original\Exception('Numeric::minlines must be and integer');
                        })
                    ->end()
                ->end()
            ->end();
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */