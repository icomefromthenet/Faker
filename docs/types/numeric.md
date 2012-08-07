#Numeric Type

The numeric type can be used to represet numbers in specify formats. It will generate a random integer for each placeholder. Please be mindful of formats that are not valid PHP int, floats, doubles. Values that exceed the rangae offered by php should be generated with the alphanumeric which will cast value as string.

**The numeric type has the following option.**

1. format - the placeholder to replace for example xxxxxxxxxx.xxx.

**Simple DSL.**

1. `x` (0-9).
2. `X` (1-9).

**To declare this type use the following format:**

```xml

<datatype name="numeric">
    <option name="format" value="xxxx" />
</datatype>

```

**A decimal my also be included:**

```xml

<datatype name="numeric">
    <option name="format" value="xxxx.xx" />
</datatype>

```


To Represent a block of formats all values up to a million using the random selector is recommended.


```xml

<random>
    <!-- 0 - 10 cents --> 
    <datatype name="numeric">
        <option name="format" value="0.0x" />
    </datatype>

    <!-- 10 - 99 cents -->
    <datatype name="numeric">
        <option name="format" value="0.Xx" />
    </datatype>

    <!-- 1.00 - 9.99 dollars -->
    <datatype name="numeric">
        <option name="format" value="X.xx" />
    </datatype>
    
    <!-- 10.00 dollars - 99.99 dollars -->
    <datatype name="numeric">
        <option name="format" value="Xx.xx" />
    </datatype>
    
    <!-- 100.00 hundred - 999.99 hundred -->
    <datatype name="numeric">
        <option name="format" value="Xxx.xx" />
    </datatype>

    <!-- 1,000.00 thousand - 9,000 dollars -->
    <datatype name="numeric">
        <option name="format" value="Xxxx.xx" />
    </datatype>

    <!-- 10,000.00 thousand - 99,999.99 thousand -->
    <datatype name="numeric">
        <option name="format" value="Xxxxx.xx" />
    </datatype>

    <!-- 100,000.00 thousand - 999,999.99 thousand -->
    <datatype name="numeric">
        <option name="format" value="Xxxxxx.xx" />
    </datatype>

    <!-- 1,000,000.00 million - 9,999,999.99 million -->
    <datatype name="numeric">
        <option name="format" value="Xxxxxxx.xx" />
    </datatype>
</random>

```



