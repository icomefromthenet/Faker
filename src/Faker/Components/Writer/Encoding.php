<?php
namespace Faker\Components\Writer;

use Faker\Components\Writer\Exception as WriterException;


/**
  *  Wrapper for iconv will encode text from in to out
  *
  *  @since 1.0.3
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  */
class Encoding
{
    /**
      * @var string the in character encoding 
      */
    protected $in;
    
    
    /**
      *  @var string the out character encoding 
      */
    protected $out;
    
    
    /**
      *  Class constructor
      *
      *  @param string $in the in encoding
      *  @param string $out the out encoding
      */
    public function __construct($in,$out)
    {
        $this->in  = $in;
        $this->out = $out;
        
        if(function_exists('\mb_convert_encoding') === false) {
            throw new WriterException('Writer::Encoding::MBString extension not installed');
        }
                       
        if($this->validateEncoding() === false) {
            throw new WriterException("writer::__construct out encoding $out OR in encoding $in is invalid");
        }
    }
    
    
    /**
      *  Encode a string to out format
      *
      *  includes work around for iconv bug https://bugs.php.net/bug.php?id=48147
      *  
      *
      *  @return string the encoded string
      *  @access public
      *  @param string a string encoded in the in encoding
      */
    public function encode($str)
    {
        $old = ini_get('mbstring.substitute_character'); 
        
        ini_set('mbstring.substitute_character', "none"); 
        
        if(strcasecmp($this->in,$this->out) !== 0) {
            return mb_convert_encoding($str, $this->out, $this->in);
        }
        
        ini_set('mbstring.substitute_character', $old); 
        
        return $str;
    }
    
    
    /**
      *  Set the in encoding method
      *
      *  @access public
      *  @param string $in 
      *  @return Faker\Components\Writter\Encoding
      */
    public function setInEncoding($in)
    {
        $this->in = $in;
        
        return $this;
    }
    
     /**
      *  Set the out encoding method
      *
      *  @access public
      *  @param string $out 
      *  @return Faker\Components\Writter\Encoding
      */
    public function setOutEncoding($out)
    {
        $this->out = $out;
        
        return $this;
    }
    
    /**
      *  Validate the encoding
      *
      *  @return boolean false if error caused
      *  @access public
      */
    public function validateEncoding()
    {
        if (@!mb_convert_encoding('an ascii string', $this->out, $this->in)) {
           return false;
        }
        
        return true;
        
    }
    
}
/* End of File */