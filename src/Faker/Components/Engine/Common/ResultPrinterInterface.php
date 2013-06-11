<?php
namespace Faker\Components\Engine\Common;

use Faker\Project;

/**
  *  Help Print a result.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 
  */
interface ResultPrinterInterface
{
    /**
     *  Gets the header for the output file
     *
     *  @access public
     *  @return string
     *
    */
    public function getHeader();
    
    /**
     *  Gets the footer for the output file
     *
     *  @access public
     *  @return string
     *
    */
    public function getFooter();
    
    /**
     *  Gets the next line from the generator or return null when
     *  no output
     *
     *  @access public
     *  @return string
     *
    */
    public function getLine();
    
    /**
     *  Get the generator composite that produce the result
     *
     *  @access public
     *  @return Faker\Components\Engine\Common\Composite\GeneratorInterface
     *
    */
    public function getGenerator();
    
    /**
     *  Sets the container
     *
     *  @access public
     *  @return void
     *  @param Faker\Project $project
     *
    */
    public function setContainer(Project $project);
    
    /**
     *  Get the ouput file name
     *
     *  @access public
     *  @return string the output filename
     *
    */
    public function getFileName();
    
}
/* End of File */