<?php
namespace Faker\Components\Engine\Common\Formatter;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\FormatterNode;
use Faker\Components\Engine\Common\Formatter\Sql;


/**
  *  Definition for the Sql Formatter 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SqlFormatterDefinition extends AbstractDefinition
{
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\FormatterNode The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        # assign mysql as a default platform
        if($this->dbalPlatform === null) {
            $this->dbalPlatform = 'mysql';
        }
        
        $platform  = $this->platformFactory->create($this->dbalPlatform);
        $formatter = $this->formatterFactory->create('sql',$platform,$this->attributes);
        
        # return a CompositeInterface Node
        return new FormatterNode('formatterNode',$this->eventDispatcher,$formatter);
    }
    
    
    /**
      *  Sets the maximum number of statements per file excluding the template contents (header and footer)
      *  will be ignored if single file mode is set.
      *  
      *  @param integer $lines the number of lines
      *  @access public
      *  @example ->maxLines(100) default is 1000 per file
      *  @return SqlFormatterDefinition
      */
    public function maxLines($lines)
    {
        $this->attribute(Sql::CONFIG_OPTION_MAX_LINES,$lines);
        return $this;
    }
    
    /**
      *  Enable single outout file mode forces formatter to agg all output into a single file
      *
      *  @access public
      *  @return SqlFormatterDefinition
      *  @example ->singleFileMode()
      */
    public function singleFileMode()
    {
        $this->attribute(Sql::CONFIG_OPTION_SINGLE_FILE_MODE,true);
        return $this;
    }
    
}
/* End of File */