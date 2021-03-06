<?php
namespace Faker\Parser\Event;

use Symfony\Component\EventDispatcher\Event;

class RowParsed extends Event
{
    
    protected $row;
    
    protected $data;
    
    public function __construct($data,$row)
    {
        $this->row = $row;
        $this->data = $data;
    }    
    
    
    public function getRow()
    {
        return $this->row;
    }
    
    
    public function getData()
    {
        return $this->data;        
    }
    
}
/* End of File */