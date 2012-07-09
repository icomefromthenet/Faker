#Common Meta Tags (Describe Schema Structure)

1. [Schema](tags.md#schema)
2. [Table](tags.md#table)
3. [Column](tags.md#column)

##Schema
The Schema tag is the first tag after the xml declaration, this tag represents a database, ***one schema per file***. 
```xml
 <schema name="schema_name" locale="en" randomGenerator="srand" generatorSeed="10000" >
 </schema>
```
Supported Attributes :

| Attribute        | Type     | Optional   | Description |
|:-----------------|---------:|:----------:|:-----------:|
| name             | (string) |    false   | Should be schema name, same case and spelling as the actual database schema. |
| randomGenerator  | (string) |    true    | Simple,srand or mersenne. The random number [generator class](generators.md) defaults to srand. |
| generatorSeed    | (integer)|    true    | The seed value to use.| 
| locale           | (string) |    true    | Added for future use. |

The randomGenerator and generatorSeed will be inherited by table / column / datatype, it's good to set a global here.

##Table
The Table tag must map to a database table, with the name attribute matching the tables name. The generate attribute tell the generator to make x passes. 
```xml
<table name="actor" generate="100" randomGenerator="simple" generatorSeed="1000">
</table>
```
Supported Attributes :

| Attribute        | Type     | Optional   | Description |
|:-----------------|---------:|:----------:|:-----------:|
| name             | (string) |    false   | Should be schema name, same case and spelling as the actual database schema. |
| randomGenerator  | (string) |    true    | Simple,srand or mersenne. The random number [generator class](generators.md) defaults to srand. |
| generatorSeed    | (integer)|    true    | The seed value to use.| 
| locale           | (string) |    true    | Added for future use. |
| generate         | integer  |    false   | The total rows to generate. | 

The randomGenerator and generatorSeed will be inherited by column / datatype.

##Column
The column must map back to a database column, the type should map to a Doctine Column or a custom column added by yourself to the extension namespace.
```xml
<column name="last_name" type="string">
</column>
```
Supported Attributes:

| Attribute        | Type     | Optional   | Description |
|:-----------------|---------:|:----------:|:-----------:|
| name             | (string) |    false   | Should be schema name, same case and spelling as the actual database schema. |
| randomGenerator  | (string) |    true    | Simple,srand or mersenne. The random number [generator class](generators.md) defaults to srand. |
| generatorSeed    | (integer)|    true    | The seed value to use.| 
| locale           | (string) |    true    | Added for future use. |
| type             | (string) |    false   | Doctrine column type. |

The randomGenerator and generatorSeed will be inherited datatype.

###Doctine Columns include:
1. array
2. simple_array
3. json_array
4  bigint
5. boolean
6. datetime
7. datetimetz
8. date
9. time
10. decimal
11. integer
12. object
13. smallint
14. string
15. text
16. blob
17. float
18. guid

You can add you own column types using the [extension namespace](extensions.md)


#Selector Tags (Describe Flow)
Selector tags are seen inside columns, they allow the generator to make simple choices on which datatype to use in a generator pass.

1. [Alternate](tags.md#alternate)
2. [Pick](tags.md#pick) 
3. [Random](tags.md#random)
4. [Swap - When](tags.md#swap-when)


##Alternate
The alternate selector allows a the generator to select a tag x (step) times then move onto the next tag until the sequence restarts.
```xml
<alternate step ="1">
    <datatype />
    <datatype />
    <datatype />
</alternate>
```
Supported Attributes:

| Attribute        | Type     | Optional   | Description |
|:-----------------|---------:|:----------:|:-----------:|
| step             | (integer)|  false     | The number of generator passes to make before moving to next tag |



##Pick
The pick uses a probability to choose between two children. Higher probabilities will skew the distribution towards the first child while lower probabilites will skew the selection to the second child. Max two datatypes per pick element.

```xml
<pick probability ="80">
    <datatype />
    <datatype />
</pick>
```
Supported Attributes:

| Attribute        | Type     | Optional   | Description |
|:-----------------|---------:|:----------:|:-----------:|
| probability      | (integer)|  false     | A whole number , 1-100 the probability to use A or B |



##Random
Will pick a child, at random. It supports more than two children. There are no guarantees on the distribution of selections and there are no further config attributes.

```xml
<random>
    <datatype />
    <datatype />
    <datatype />
    <datatype />
</random>
```

##Swap-When
This format gives very precise control on the distribtion of values. If you wanted 5 of x then 10 of y this selector should be used. The counter will start at the first child and only skip to next child after x (switch) passes of the generator. Once the sequence has been exhausted it is restarted.

```xml
<swap>
 <when switch="100">
  <datatype />
 </when>
 <when switch="5">
    <datatype />
 </when>
</swap>

```
Supported Attributes:

| Attribute        | Type     | Optional   | Description |
|:-----------------|---------:|:----------:|:-----------:|
| switch           | (integer)|  false     | The number of generator passes to make before moving to next tag |


Note two selector tags ```<swap />``` and ```<when />```