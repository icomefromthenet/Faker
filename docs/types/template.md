#Template Type

The template type directs the generator to use a twig template founder under the template directory. Use this type when calculated values are needed as columns values are available for that row and the assigned random-generator, locale and utilites.

**The template has the following options:**

1. file  - Name of the file template (include extension '.twig').

The range returns string value to return numbers make sure no symbols that prevent type casting by doctrine DBAL.

**To declare a range use the following format:**

```xml
<datatype name="template">
    <option name="file" value="table.column.twig" />
</datatype>
```

Best to use the table and column and schema names in your template filename.

The following default namespace are available

1. faker_locale -   Locale assigned.
2. faker_generator - Generator assigned.
3. faker_utilities - Utilites has access to core di class.
