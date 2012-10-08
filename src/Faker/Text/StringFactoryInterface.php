<?php
namespace Faker\Text;

/**
  *  Static Factory for SimpleString
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  */
interface StringFactoryInterface
{

    /**
      *  Create new instance of the SimpleStringInterface 
      *
      *  @return SimpleStringInterface
      *  @param string $string a inital string
      *  @param SimpleStringInterface $provider the provider to use default to null
      *  @param string $encoding defaults to UTF-8
      */
    public static function create($string, SimpleStringInterface $provider = null ,$encoding = 'UTF-8');
    
}
/* End of File */