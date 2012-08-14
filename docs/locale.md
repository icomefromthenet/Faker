#Locale
Many of the build-in (Text,Alphanumeric) datatypes use a locale. Currently the only an english locale is include to include you own follow the guide below.

## Simple conventions

1. All folder and file names must be **lowercase**.
2. They must be registered to a factory via the bootstrap in root of the project directory.
3. No limit on class names, as they are referenced by the short name.
4. Need a unique short name.
5. Must use the extension namespace ```\Faker\Extensions\Locale``.


## Create the File

1. Navigate to project directory open __extension/faker/locale__
2. Create the new file xxxxLocale.php


## Fill in the blanks

I have included an example of the english locale, need to change the class names and add your own filler text and alphabet.

```php

<?php
namespace Faker\Extension\Locale;

use Faker\Text\StringFactoryInterface,
    Faker\Locale\LocaleInterface;

class EnglishLocale implements LocaleInterface
{
    /**
      *  @var StringFactoryInterface the string factory
      */
    protected $string_factory;
    
    
    protected static $words = array(
                        'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.',
                        'Curabitur sed tortor. Integer aliquam adipiscing lacus.',
                        'Ut nec urna et arcu imperdiet ullamcorper. Duis at lacus.',
                        'Quisque purus sapien, gravida non, sollicitudin a, malesuada id,',
                        'erat. Etiam vestibulum massa rutrum magna. Cras convallis',
                        'convallis dolor. Quisque tincidunt pede ac urna. Ut tincidunt',
                        'vehicula risus. Nulla eget metus eu erat semper rutrum.',
                        'Fusce dolor quam, elementum at, egestas a,',
                        'scelerisque sed, sapien. Nunc pulvinar arcu et pede.',
                        'Nunc sed orci lobortis augue scelerisque mollis.',
                        'Phasellus libero mauris, aliquam eu, accumsan sed,',
                        'facilisis vitae, orci. Phasellus dapibus quam quis diam.',
                        'Pellentesque habitant morbi tristique senectus et netus et',
                        
                        //shortened to keep use sane
                );
    
    /**
      *  Fetch the consonants from alphabet
      *
      *  @access public
      *  @return \Faker\Test\SimpleTextInterface
      */
    public function getConsonants()
    {
        $factory = $this->string_factory;
        
        return $factory::create('BCDFGHJKLMNPQRSTVWXYZ');
    }
    
    /**
      *  Fetch the vowels from alphabet
      *  
      *  @access public
      *  @return \Faker\Test\SimpleTextInterface
      */
    public function getVowels()
    {
        $factory = $this->string_factory;
        
        return $factory::create('AEIOU');
    }
    
    /**
      *  Fetch the letters of the alphabet
      *  
      *  @access public
      *  @return \Faker\Test\SimpleTextInterface
      */
    public function getLetters()
    {
        $factory = $this->string_factory;
        
        return $factory::create('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }
    
    /**
      *   Fetch an array of filler text
      *   
      *   @access public
      *   @return array string[]
      */
    public function getFillerText()
    {
        return self::$words;  
    }
    
    /**
      *  Fetch Hexdecimal alphabet 
      * 
      *  @access public
      *  @return \Faker\Text\SimpleTextInterface
      */
    public function getHex()
    {
        $factory = $this->string_factory;
        
        return $factory::create('0123456789ABCDEF');
    }
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param StringFactoryInterface $factory
      *  @return void
      */
    public function __construct(StringFactoryInterface $factory)
    {
        $this->string_factory = $factory;
    }
    
        
}
/* End of File */

```


## Register with bootstrap

Open the bootstrap file under _project/extension/bootstrap.php_ and scroll down to the section shown below.

```php
<?
/*
|--------------------------------------------------------------------------
| Faker Locales
|--------------------------------------------------------------------------
|
| Register a new Locale, which provide locale specific text to the generators.
|
| LocaleFactory::registerExtension('french','Faker\\Components\\Extension\\Locale\\FrenchLocale');
|
*/ 

  LocaleFactory::registerExtension('french','Faker\\Components\\Extension\\Locale\\FrenchLocale');

```

Now refer to the locale by the shortname. 

```xml
<schema locale="french" />
```

Above will set the default locale for entire schema.


```xml
<table locale="french" />
```

Above will set the default locale for the current table.


```xml
<column locale="french" />
```

Above will set the default locale for the current column.



```xml
<datatype locale="french" />
```

Above will set the default locale for the current datatype.