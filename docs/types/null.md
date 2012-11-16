# NULL Type

The null type is used when a column can contain null values, and you wish
to simulate this behaviour also can effective placeholder placeholder.

There are <strong>no options</strong> for this type.

**To declare this time the following format is used:**

```xml

<datatype name="nulltype">
</datatype>

```

or

```xml

<datatype name="nulltype" />

```

**A better use is to combine with the random selector:**

```xml

<random>
    <datatype name="nulltype" />
    ... other type
</random>

```