<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Templating\Loader as TemplateLoader;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Fetches data from a source using a twig templates to render values,
 * this allow for any extra transformations that might be instance specific
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.5
 */  
class FromSource extends Template implements BindDataInterface
{
   
    /**
     * Data for sources to push to template
     * 
     * @var array of data to send to template
     */ 
    protected $dataFromSources;
    
    
    
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
            
             
    	if(true === isset($this->dataFromSources[0])) {
    		$result =  $this->template->render(array('values'=>$values,'sources'=> $this->dataFromSources[0]));
    	} else {
            $result =  $this->template->render(array('values'=>$values,'sources'=> array()));
    	} 
    
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
        parent::getConfigTreeExtension($rootNode);
        
        $rootNode
            ->children()
                ->scalarNode('source')
                 ->info('Name of the source to bind')
                 ->cannotBeEmpty()
                 ->isRequired()
                ->end()
            ->end();
            
        return $rootNode;
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        $valid = false;
        
        if(parent::validate()) {
            // datasources are bound to PARENTS during a compile step
            $valid = true;
        }
        
        
        return $valid;
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