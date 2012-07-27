#Writing Extensions
An extension namespace is provided under ```\Faker\Extensions``, Don't be affarid of writing you will use them often.

1. Import custom data from xml , csv , webservices and other databases
2. Values have unique constrains.
3. Values need to have a particular distribtion e.g (bell curve)
4. Want to use a custom format.


I will show you how to create own datatypes, platforms and column and formatters.

### Formatters.
A formatter is used to translate the generated data into a set of text files. A SQL and PHPUnit are included and I will show below you how to write a csv formatter. This tutorial will give the skills to write your own.

#### Create the extension file

All folder and file names must be **lowercase**.

Navigate to project directory, open the directory extension/components/faker/formatter.

Create a file called csv.php and copy in the follow code block.

```php
<?php
namespace Faker\Extension\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Doctrine\DBAL\Platforms\AbstractPlatform,
    Faker\Components\Writer\WriterInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Formatter\BaseFormatter,
    Faker\Components\Faker\Formatter\FormatterInterface;
    
    
class csv extends BaseFormatter implements FormatterInterface
{

}


```
Note the class name is lowercase.

#### Implement the base class

A formatter must extend the ```Faker\Components\Faker\Formatter\BaseFormatter``` this provides a basic set of config options and event binding, It does have a number of abstract methods that are covered below and please copy the code blocks as they are covered.

**function getName()**
```php
    public function getName()
    {
        return 'csv';
    }
 
```
This method needs to return the name of the formatter that is registrted with the formatter factory in the extension bootstrap file.

**function toxml()**
```php
    public function toXml()
    {
        return '';
    }
```
This method is needed to translate the writer to xml string. Used during the schema analysis but in your extension this not needed so return empty string.

**function getConfigExtension()**
```php
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        $rootNode->children()
                    ->booleanNode('headerRow')
                        ->treatNullLike(true)
                        ->defaultValue(true)
                        ->info('Will include a header row for each new set')
                    ->end()
                ->end();
        
        return $rootNode;    
    }
```
This method is used to add config options since I would like to have a header row I am going to add this as an option. The Symfony2 config extension is used here. If you don't extra config options return the first argument only.

I have a boolean option called ```headerRow``` that will default to true.

**function getOuputFileFormat()**
```php
    public function getOuputFileFormat()
    {
        return '{prefix}_{body}_{suffix}_{seq}.{ext}';
 
    }
```

This is the default output file format. If the config option is not included this value will be used.

**function getDefaultOutEncoding()**
```php
    public function getDefaultOutEncoding()
    {
        return 'UTF-8';
    }

```
This is the encoding the output file will use by default.

You have not implemented the BaseFormatter, now need to implement the interface.

##### The FormatterInterface

The interface ```Faker\Components\Faker\Formatter\FormatterInterface``` provided the event hooks which the generator will call during a generation run.

The events include:

|Event Name      | Description |
|:---------------|:------------|
|onSchemaStart   | First event called and only once
|onSchemaEnd     | Last event called and only once
|onTableStart    | Called at start of a table
|onTableEnd      | Called at the end of a table
|onRowStart      | Called at the start of a new row
|onRowEnd        | Called at the end of a new row
|onColumnStart   | Called at the start of a column
|onColumnGenerate| Called after value been generated for column
|onColumnEnd     | Called after the column has finished.
    
The first event we want for