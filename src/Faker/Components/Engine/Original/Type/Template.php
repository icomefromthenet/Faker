<?php
namespace Faker\Components\Engine\Original\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Template extends Type
{

    
    protected $template;
    
    
    public function generate($rows, $values = array())
    {
        
        if($this->template === null) {
            $template    = $this->getOption('file');
            $temp_string = $this->getOption('template');
            $loader      = $this->getUtilities()->getTemplatingManager()->getLoader();
            
            $vars = array('faker_utilities' => $this->getUtilities(),
                          'faker_locale' => $this->getLocale(),
                          'faker_generator' => $this->getGenerator()
            );
            
            if($template !== false) {
                $this->template = $loader->load($template,$vars);      
                
            } else {
                $this->template = $loader->loadString($temp_string,$vars);      
            }
        }
            
        return $this->template->render($values);
    
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
                ->scalarNode('file')
                    ->defaultValue(false)
                    ->treatNullLike(false)
                    ->info('File name of the template')
                    ->example('file under the tempplate dir')
                ->end()
                ->scalarNode('template')
                    ->defaultValue(false)
                    ->treatNullLike(false)
                    ->info('A template string to use')
                    ->example('{{ var1 }} + {{ var2 }}')
                ->end()
            ->end();
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        $template    = $this->getOption('file');
        $temp_string = $this->getOption('template');
        
        if($template === false && $temp_string === false) {
            throw new FakerException('Template Type:: must set either a file or a template string');
        }
        
        
        if($template !== false) {
            $loader      = $this->getUtilities()->getTemplatingManager()->getLoader();
            if($loader->getIo()->exists($template) === false) {
                throw new FakerException('Template::File Does not Exists at '. $template);
            }  
        }
        
        return true;
    }
    
    //  -------------------------------------------------------------------------

}

/* End of class */