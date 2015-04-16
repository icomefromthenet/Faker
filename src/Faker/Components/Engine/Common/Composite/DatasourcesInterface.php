<?php
namespace Faker\Components\Engine\Common\Composite;

use Faker\Components\Engine\Common\Datasource\DatasourceInterface;

/**
  *  Interface allows a composite node to contain many datasources
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface DatasourcesInterface
{
    /**
      *  Return all assigned datasources.
      *
      *  @return array[Faker\Components\Engine\Common\Datasource\DatasourceInterface] 
      *  @access public
      */
    public function getDatasources();
    
    
    /**
      *  Assign datasource to this composite node.
      *
      *  @param Faker\Components\Engine\Common\Datasource\DatasourceInterface a source to add
      *  @access public
      */
    public function addDatasource(DatasourceInterface $source);
    
}
/* End of File */
