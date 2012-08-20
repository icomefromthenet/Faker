#Text Block Type

This type uses the text data provided by locale fillertext. The number of lines is randomly chosen using the min and max as range.

**Basic Example**

```xml

<datatype name="text">
    <option name="paragraphs" value="2" />
</datatype>

```

**Type has the following options:**

1. paragraphs(4)    - Number of paragraphs to produce.
2. minlines  (4)    - Minimum number of lines to include per paragraph.
3. maxlines  (200)  - Maximum number of lines to include per paragraph.


**Minlines Example**

```xml

<datatype name="text">
    <option name="paragraphs" value="2" />
    <option name="minlines"   value="3" />
</datatype>

```

This would generate 2 paragraphs with minimum of 3 lines and the default max of 200 lines.


**Range Example**

```xml

<datatype name="text">
    <option name="paragraphs" value="2" />
    <option name="minlines"   value="3" />
    <option name="maxlines"   value="5" />
</datatype>

```

This would generate 2 paragraphs with minimum of 3 lines and the max of 5 lines.

