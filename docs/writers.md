#What is a Writer?

A writer controls how the generated data is placed into a file. Each writer targets a platform provided by doctrine DBAL. The platform will convert a php primative (integer|string|float|bool|DateTimes) into a valid representation for the platform. A writer targets a format a sql and phpunit formatter are provided. You can add your own formatter as an extension.

##SQL Formatter
As it's name suggests this formatter will translate your data into a sql text file, by default each table is placed into a seperate set of files, with a maxium of 1000 lines per file. You can change this behaviour with the following options.

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

####OutEncoding
By default all text is encoded as UTF-8 if you want to use another encoding for example ASCII you can use this option. Only those allowed with [mb_convert_encoding](http://php.net/manual/en/function.mb-convert-encoding.php])
```xml
<writer platform="mysql" format="sql" outEncoding="utf-8" />
```

##Phpunit Formatter
This formatter will output the generated data into phpunit dataset xml file, If are using phpunit-dbunit to seed your database this is a life saver (IMOP). It supports a single attribute outFileFormat. XML specifies encoding as utf-8 the option to switch has been disabled.

####OutFileFormat
You might want to change the default out format, you can use this attribute.

```xml
<writer platform="mysql" format="phpunit" outFileFormat="{prefix}_{body}_{suffix}.{ext}" />
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


