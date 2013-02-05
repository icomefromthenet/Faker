<?php

namespace Faker\Components\Engine\Original;

use Faker\Io\IoInterface;

/**
  *  Class Writer
  */
class Writer 
{

  
  //------------------------------------------------------------------

   /**
    * Class Constructor
    *
    *  @param Faker\Io\IoInterface $Io
    */
    public function __construct(IoInterface $Io){
        $this->setIo($Io);
    }



    //--------------------------------------------------------------------
    /**
     * Input Output controller
     *
     *  @var IoInterface
    */
    protected $io;

   /**
    * Fetches the Io Class
    *
    * @return IoInterface
    */
    public function getIo(){
        return $this->io;
    }

    /**
    * Sets the IO class
    *
    *  @param IoInterface $io
    */
    public function setIo(IoInterface $io) {
        $this->io = $io;

        return $this;
    }


    //---------------------------------------------------------------------

}
/* End of File */
