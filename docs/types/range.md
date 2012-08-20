#Range Type

The range is suited to generate a series of values that increase linearly or randomly and where the auto increment type will grow indefinitely the range accepts a max and will restart when achieved.

**The range type has the following options:**

1. max    - The highest possible value.
2. min    - The smallest value.
3. step   - The value to increment on each loop.
4. random - To use a random value from the range and not a step value.
5. round  - Number of decials places to round each value on.

The range returns numeric values i.e. not return a string.

Be aware of perision issues with [floating point numbers](http://php.net/manual/en/language.types.float.php).

**To declare a range use the following format:**

```xml
<datatype name="range">
    <option name="max" value="100" />
    <option name="min" value="1" />
    <option name="step" value="5" />
</datatype>
```

**Random value from range rounded to 0 places**

```xml
<datatype name="range">
    <option name="max"    value="100" />
    <option name="min"    value="1.5" />
    <option name="random" value="true" />
    <option name="round"  value="0" />
</datatype>
```

Will return a float value between 1.5 and 100 rounded up to highest integer (effective range 2 - 100). If you have an integer column make sure not to exceed the PHP Max Integer Size.


**Random value from range not rounded**

```xml
<datatype name="range">
    <option name="max"    value="100" />
    <option name="min"    value="1" />
    <option name="random" value="true" />
</datatype>
```

Will return a float value between 1 and 100.

**Using Window Step**

Using a range within a join table (composite key) a method is necessary to keep key combinations unique. A window function will move the minimum __up__ to an **X value** after the __first iteration__.

The advanced example use the window to ensure film and actor relations remain unique. 

The `random step` and `window function` are mutually exclusive (one or other).

```xml
<datatype name="range">
    <option name="max"        value="100" />
    <option name="min"        value="1" />
    <option name="step"       value="1" />
    <option name="windowStep" value="1" />
</datatype>
```

First iteration produce values 1,2,3,4 the second iteration produce values 2,3,4,5 third iteration 3,4,5,6.

