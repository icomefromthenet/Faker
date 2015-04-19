<?php 

namespace Faker\Components\Engine\Common\Type;

interface BindDataInterface 
{
    
    /**
     * Binds data for next run
     * 
     * @param array[mixed]
     */ 
    public function bindData(array $data);
    
}
/* End of Interface */