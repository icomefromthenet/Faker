<?php
namespace Faker\Tests\Engine\XML\Mock;

use Faker\Components\Engine\Common\Datasource\PHPDatasource;


class MockPHPSource extends PHPDatasource
{
    
   protected $dataIterator;
   
    /**
     * Return the full dataset assigned by the implementer;
     * 
     * @return Iterator
     */ 
    public function getIterator()
    {
        if($this->dataIterator === null) {
            
            $this->dataIterator = new ArrayIterator();
            
            $this->dataIterator->append(array('value'=>1));
            $this->dataIterator->append(array('value'=>2));
            $this->dataIterator->append(array('value'=>3));
            $this->dataIterator->append(array('value'=>4));
        }
        
        return  $this->dataIterator;
    }
    
    /**
     * Sets the iterator to use
     * 
     * @param Iterator the data iterator to use
     */ 
    public function setIterator(\Iterator $it)
    {
       
    }
}
/* End of class */

