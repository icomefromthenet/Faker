<?php 
namespace Faker\Components\Engine\Common\Datasource;

use Faker\Components\Engine\Common\OptionInterface;

/**
 * A datasource.
 * 
 * The expected lifecycle
 * 
 * 1. Validated Before Init
 * 
 * 2. Datasource are inited before generation starts.
 * 
 * 3. Datasource fetch a new row of data on every row in tables that reference it.
 * 
 * 4. Are reset when table they are referenced in finish.
 * 
 * 5. A final cleanup method is called when the generator is finished
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.5
 */ 
interface DatasourceInterface extends OptionInterface
{
    
    /**
     * This init method is called before any generation is commenced
     * 
     * Can be used to int database connections
     * 
     * @access public
     * @return void
     */ 
    public function initSource();
    
    /**
     * Called during the generator execution, for each needed row
     * 
     * Where fetch a row of data, from interal cache or a database call
     * 
     * @access public
     * @return array of data using a hash index
     */ 
    public function fetchOne();
    
    /**
     * This method is called when the node the
     * source is referenced in has finished its processing
     * 
     * @access public
     * @return void
     */ 
    public function flushSource();
    
    /**
     * Called when the source is no longer needed by any nodes, usually
     * at the end of a generation run.
     * 
     * Can be used to cleanup database references, etc...
     * 
     * @access public
     * @return void
     */ 
    public function cleanupSource();
    
    
}
/* End of Interface */
