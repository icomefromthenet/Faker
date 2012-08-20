#Date and DateTime Type

This type will enable's the definition of a date, with each loop having an optional modify (like the php modify method) with a also optional max. The doctine platform will format the DateTime for your database. Also supports a random date between a given range.

**This type has the following options:**

1. start  - The strtotime starting date.
2. max    - Optional strtotime max date.
3. modify - Optional strtotime modify string.

**Date with Step and a Max**

```xml
    <datatype name="date">
        <option name="start"  value="today" />
        <option name="modify" value="+1 week" />
        <option name="max"    value="today +10 weeks" />
    </datatype>
```

**Date with a Step Value**

The above would give the starting date as today would increment the date by 1 week on each loop and when date becomes greater than 10 weeks it will reset back to today.

```xml
    <datatype name="date">
        <option name="start"  value="today" />
        <option name="modify" value="+1 week" />
    </datatype>
```

The above would contine to increment with no max set. Be careful when not specifing a max, if the the generator increases from 100 rows to 1 million the last row would have a date of 1 million +weeks into the future. 

**Fixed Date**

```xml
    <datatype name="date">
        <option name="start" value="today" />
    </datatype>
```

The above generates a fixed date (today) on every loop, like a constant for a datetime.


**A Random date Step between Start and Max**

```xml
    <datatype name="date">
        <option name="start"  value="today" />
        <option name="max"    value="+1 year" />
        <option name="random" value="true" />
    </datatype>
```

This tells the generator to produce a random date between the start and max.

