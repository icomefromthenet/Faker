#What is a Writer?

A writer controls how the generated data is placed into a file. Each writer targets a platform provided by doctrine DBAL. The platform will convert a php primative (integer|string|float|bool|DateTimes) into a valid representation. A writer also targets a format currently a sql and phpunit formatter are provided. You can add your own formatters into the extension namespace as well as your own platforms.

##Supported Formaters

###SQL Formatter
As it's name suggests this formatter will ouput your data into sql text file, by default each table is placed into a seperate set of files , with a maxium of 1000 lines per file. You can change this behaviour with the following options.

####SingleFileMode
This will place all output into a single file, mutually exclusive with maxLines and splitOnTable,
```xml
<writer platform="mysql" format="sql" singleFileMode="true" />
```

####MaxLines
This will restrict max number of lines per file.
```xml
<writer platform="mysql" format="sql" maxLines="500" />
```

####SplitOnTable
This would disable/enable (default enabled) the split action that occurs on each new table
```xml
<writer platform="mysql" format="sql" splitOnTable="false" />
```

####OutFileFormat
If you use singleFileMode you might want to change the default output file name format.
```xml
<writer platform="mysql" format="sql" outFileFormat="{prefix}_{body}_{suffix}.{ext}" />
```

###Phpunit Formatter
This formatter will output the generated data into phpunit dataset xml file, If are using phpunit-dbunit to seed your database this is a life saver (IMOP). It supports a single attribute outFileFormat.

####OutFileFormat
You might want to change the default out format, you can use this attribute.

```xml
<writer platform="mysql" format="sql" outFileFormat="{prefix}_{body}_{suffix}.{ext}" />
```

##Writer Examples

**Mysql** platform with **Sql** format with all data in single file.

```xml
<writer platform="mysql" format="sql" singleFileMode="true" />
```

**Sqlite** platform with **Phpunit** format.

```xml
<writer platform="sqlite" format="phpunit" />
```


