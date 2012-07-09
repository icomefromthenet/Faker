# Getting Started

After reading this guide you will be ready to being testing your databases.

## Step 1. Install.

Installing is easy you can use [my pear channel](http://www.icomefromthenet.com/pear/) hosted on github.
 
     pear channel-discover icomefromthenet.github.com/pear
     pear install icomefromthenet/Faker

## Step 2. Setup Project.

Faker lives in your project, the schema files and output can be commited to your repo. (Though I suggest not pushing the ouput files).

     mkdir faker
     cd faker
     faker faker:init

These commands will create a directory under your project (assuming linux/mac). Faker is accessed using the executable called faker and the command faker:init. You will see output listing the files copied into the directory.

Now its time to configure the database connection, if your going to use the analyser. If your building your schema by hand this is not a necessary step you can go to setp 4 and start filing in.

    faker faker:configure

You will be asked a list of questions, after the last quesition a config file will be written to the config directory. There is only one config file per project.

## Step 3. Analyse your schema.

As a shortcut to building you schema fake will use doctrine schema manager to build a starting file.

     faker faker:analyse

Will write a schema.xml file to the `sources` directory in your project folder

Faker also accept a pear [db dsn](http://pear.php.net/manual/en/package.database.db.intro-dsn.php) using the --dsn argument. 


## Step 4. Fill it in.

After running analyse you will have a schema file 

```xml
<?xml version="1.0"?>
<schema name="sakila">
 <writer platform="mysql" format="sql" />
 <table name="actor" generate="100">
  <column name="actor_id" type="smallint">
   <datatype name="autoincrement">
    <option name="start" value="1" />
    <option name="increment" value="1" />
   </datatype>
  </column>
  <column name="first_name" type="string">
   <alternate step="1">
    <datatype name="alphanumeric">
     <option name="format" value="ccccc" />
    </datatype>
    <random>
     <datatype name="alphanumeric">
      <option name="format" value="CCccc" />
     </datatype>
     <datatype name="alphanumeric">
      <option name="format" value="Ccccc" />
     </datatype>
    </random>
   </alternate>
  </column>
  <column name="last_name" type="string">
   <datatype name="alphanumeric">
    <option name="format" value="ccccc" />
   </datatype>
  </column>
  <column name="last_update" type="datetime">
   <datatype name="date">
    <option name="start" value="today" />
    <option name="modify" value="+1 week" />
    <option name="max" value="today +10 weeks" />
   </datatype>
  </column>
 </table>
</schema>
```

Lets break this down: 

#### Opening Tag
```xml
<?xml version="1.0"?>
<schema name="sakila">
</schema>
```
There must be only one ``schema`` per file, and the ``name`` attribute must match the schema name in the database.

#### Writer
```xml
<writer platform="mysql" format="sql" />
```
After the schema tag a writter needs to be declared see [writers](writers.md) page for more.

#### Table
```xml
<table name="actor" generate="100">
</table>
```
The table tag should represent a database table, the name must be the same as its counterpart and the number of rows to generate must be included see the [table tag](tags.md#table) for more.


#### Columns
```xml
<column name="first_name" type="string">
</column>
```
A table is a collection of columns, each [column](tags.md#column) must map to a column in the current table. The type tag must be a valid Doctrine DBAL column or a custom column added via the extension namespace.


#### Datatype
```xml
<datatype name="date">
    <option name="start" value="today" />
    <option name="modify" value="+1 week" />
    <option name="max" value="today +10 weeks" />
</datatype>
```
Inside a column a [datatype](types/index.md) can be placed, or a [selector](type.md#alternate) to control which type is used. A type usually has options which are added via the options tag.   

## Step 5. Generate Generate Generate.

       faker faker:generate
       
       or
       
       faker faker:generate schema.xml

To start the generator you must have a completed schema inside the **sources dir**. The command will assume the schema is called schema.xml, if your using multiple's then you can pass the schema name as the first argument.

## Conclusion.
You should now have data files generated under the **dump directory**, you can run the command again, it will overrite the files in the directory. I would recommand you add the dump dir to source code ignore list.



