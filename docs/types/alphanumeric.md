#AlpahNumeric Type

The alphanumeric is a favourite to generate small random blocks of text for items like names , locations and other descriptions.

**There is a small DSL that provieds limited control on the output.**

    1.   C, c, E - any consonant (Upper case, lower case, any)
    2.   V, v, F - any vowel (Upper case, lower case, any)
    3.   L, l, D - any letter (Upper case, lower case, any)
    4.   X       - 1-9
    5.   x       - 0-9
    6.   H       - 0-F


**The format CcVDx would give:**

    'C' = Upper case consonant
    'c' = Lower case consonant
    'V' = Upper case vowel
    'D' = Any letter on random case
    'x' = a number between 0-9


**Options:**

1. Format (Required) the dsl string.
2. repeatMax maximum number of times to repeat the format string.
3. repeatMin minimum number of times to repeat the format string.


**To declare a alphanumeric type:**
```xml
<datatype name="alphanumeric">
    <option name="format" value="CcVDx" />
</datatype>
```

**To Repeat between 0 and 5 times.**

<datatype name="alphanumeric">
    <option name="format"    value="CcVDx" />
    <option name="repeatMin" value="0" />
    <option name="repeatMax" value="5" />
</datatype>

The minimum size of the string is 5 characters and the maxium is 25 characters. The repeat options must be positive integers.

**To combine a prefix the constant datatype can be used, shown below.**

```xml
<datatype name="constant">
    <option name="value" value="Index_" />
</datatype>
<datatype name="alphanumeric">
    <option name="format" value="CcVDx" />
</datatype>
```

Would give return for example 'Index_FgEp6'.



