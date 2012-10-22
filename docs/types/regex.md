#Regex Type

The Regex type is perfect for representing text and numeric values.

**The Regex type has a single option:**

1. format     - The regex to use, no delimiters.

**The Regex can be declared as flows:**

```xml
<datatype name="regex">
    <option name="format" value="\[a-z\]{1,4}" />
</datatype>
```

The above will return a letter between a-z 1 to four times.

See the project page for [supported regex meta-characters](https://github.com/icomefromthenet/ReverseRegex#writing-a-regex). 

