#Type Index
1. [Alphanumeric](alphanumeric.md) - Alphanumeric values
2. [AutoIncrement](autoincrement.md) - Incrementing integers
3. [Boolean](boolean.md) - Booelan values
4. [City](city.md)  - City Names
5. [Constant](constant.md) - A constant value
6. [Country](country.md) - ISO Country names
7. [Date](date.md)  - Dates and more dates
8. [Email](email.md) - Email addresses     
9. [NullType](null.md) - Generate null values
10. [Numeric](numeric.md)- numbers only
11. [People](people.md) - People names
12. [Range](range.md) - A number range
13. [Template](template.md) - For calculated  columns.
14. [Regex](regex.md) - Text values using a Regex great for varaible formats.

## Writing your own DataType.

Every project you will find a situation not coverd by one of the built-in datatypes. 

An extension namespace is provided under ```\Faker\Extensions```. This namespace maps to the extension folder under the project directory.

All Extension must following the following conventions:

1. All folder and file names must be **lowercase**.
2. They must be registered to a factory via the bootstrap in root of the project directory.
3. No limit on class names, as they are referenced by the short name.
4. Need a unique short name.
5. Must use the extension namespace ```\Faker\Extensions``. 
6. For datatypes (most common extension) make use of the assigned random generator and locale. 

I have provided an example below that can be used as a base for **datatypes**.

```php
    namespace Faker\Extension\Faker\Type;
    
    use Faker\Components\Engine\Original\Exception as FakerException,
        Faker\Components\Engine\Original\Type\Type,
        Faker\Components\Engine\Common\Utilities,
        Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
    
    class ClassName extends Type
    {
    
        //  -------------------------------------------------------------------------
    
        /**
         * Generate a value
         * 
         * @return string 
         */
        public function generate($row,$values = array())
        {
            #fetch an option.    
            $format = $this->getOption('format');
            
            #fetch the locale
            $locale = $this->getLocale();
            
            # fetch the generator
            $generator = $this->getGenerator();
            
            return $string;
        }
        
        
       
        //  -------------------------------------------------------------------------
        
        /**
         * Generates the configuration tree builder.
         *
         * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition 
         */
        public function getConfigExtension(ArrayNodeDefinition $rootNode)
        {
            return $rootNode
                ->children()
                    ->scalarNode('format')
                        ->info('Text format to generate')
                        ->example('xxxxx ccccCC')
                    ->end()
                ->end();
                
        }
        
        
        //  -------------------------------------------------------------------------
    }
    
    /* End of File */
```

There can be many instances of a type, you may use properties without effecting other instances. 

All types need to inherit the base type. If you need to have config options they can be added via the method ```getConfigExtension()```, using the [symfony2 config](https://github.com/symfony/Config) component you setup expected config values and provide validation routines. If yoy do not need any options just return the ```$rootNode```.

The ```generate()``` method is called on each loop and needs to return the a value. It has two paramaters, first $rows the row number starting at 1 and the second $values an associative array of generated values for this row (not all values only preceeding).

After you have created the type you will need to register it with the bootstrap by opending _project/extension/bootstrap.php_  navigate to the section and add as shown. 

```php
/*
|--------------------------------------------------------------------------
| Faker DataTypes
|--------------------------------------------------------------------------
| 
| To Add a new datatype a it must be registerd registered, the  object
| implements TypeConfigInterface,CompositeInterface and TypeInterface.
|
| You may also override built in types using the same key.
|
| Example:
|
| TypeFactory::registerExtension('vector','Faker\\Components\\Extension\\Faker\\Type\\Vector');
*/

    TypeFactory::registerExtension('password','Faker\\Extension\\Faker\\Type\\Password');
```

You can now load the extension datatype using the shortname.

```xml
 <datatype name="short_name">
    <option name="an_option" value="1"/>
 </datatype>
```