<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Template extends Type
{

    
    protected $template;
    
    
    public function generate($rows, $values = array())
    {
        $template = $this->getOption('file');
        $loader = $this->getUtilities()->getTemplatingManager()->getLoader();
    
        if($loader->getIo()->exists($template) === false) {
            throw new FakerException('Template::File Does not Exists at '. $template);
        }
    
        $this->template = $loader->load($template,array('faker_utilities' => $this->getUtilities(),'faker_locale' => $this->getLocale(),'faker_generator' => $this->getGenerator()));
    
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
                    ->isRequired()
                    ->info('File name of the template')
                    ->example('file under the tempplate dir')
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

/* End of class */