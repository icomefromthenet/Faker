#Template Type

The template type directs the generator to use a twig template found under the template directory. Use this when calculated values are needed as columns values are available for that row.

Be careful with column placement they only receive values from preceeding columns this behaviour can be used to chain calculated columns.

**The template has the following options:**

1. file  - Name of the file template (include extension '.twig').

If your looking for numbers the template returns string make sure there are no symbols that prevent type casting by doctrine DBAL platform.

**To declare a template use the following format:**

```xml
<datatype name="template">
    <option name="file" value="table.column.twig" />
</datatype>
```

**Indicate a string instead of a file**

```xml
<datatype name="template">
    <option name="template" value="{{ 1 + 1 }}" />
</datatype>
```


Tip: to use the table and column and schema names in your template filename.

**The following default namespace are available inside the template:**

1. faker_locale    - Locale assigned.
2. faker_generator - Generator assigned.
3. faker_utilities - Utilites has access to core di class.
