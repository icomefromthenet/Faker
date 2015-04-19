<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Templating\Loader as TemplateLoader;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Use twig templates to return values
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */  
class Template extends Type implements BindDataInterface
{

    
    protected $template;
    
    /**
      *  @var \Faker\Components\Templating\Loader 
      */
    protected $templateLoader;
    
    /**
     * Data for sources to push to template
     * 
     * @var array of data to send to template
     */ 
    protected $dataFromSources;
    
    
    /**
      *  Class Constructor
      *
      *  @param \Faker\Components\Templating\Loader $loader
      */
    public function __construct(TemplateLoader $loader)
    {
        $this->templateLoader = $loader;
    }
    
    //------------------------------------------------------------------
    
    
    public function generate($rows, &$values = array(),$last = array())
    {
        
        if($this->template === null) {
            $template    = $this->getOption('file');
            $temp_string = $this->getOption('template');
            $loader      = $this->templateLoader;
            
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
            
        $result =  $this->template->render(array('values'=>$values,'sources'=> $this->dataFromSources));
    
        # clear bound data
        $this->bindData(array());
    
        return $result;
    }

    
    //  -------------------------------------------------------------------------

       /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        $rootNode
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
            
        return $rootNode;
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        parent::validate();
        
        $template    = $this->getOption('file');
        $temp_string = $this->getOption('template');
        
        if($template === false && $temp_string === false) {
            throw new EngineException('Template Type:: must set either a file or a template string');
        }
        
        
        if($template !== false) {
            $loader      = $this->templateLoader;
            if($loader->getIo()->exists($template) === false) {
                throw new EngineException('Template Type::File Does not Exists at '. $template);
            }  
        }
        
        return true;
    }
    
    //  -------------------------------------------------------------------------
    # BindDataInterface
    
    /**
     * Binds data for next run
     * 
     * @param array[mixed]
     */ 
    public function bindData(array $data)
    {
        $this->dataFromSources = $data;
    }
}

/* End of class */