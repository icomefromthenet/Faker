## Whats is a Generator

A generator makes pseudorandom random numbers. PHP includes 2 pseudorandom number generators ```rand()``` and ```mt_rand()```. Faker allows multiple generators to be included inside a schema. A default can be set on schema , table and column each datatype can have their own generator too.

This has not answerd why? Pseudorandom random number generators are **seeded** with an inital value. Running two random generators that use the same algorithm will same seed willl see both return  the same values on consecutive calls. This is why there called pseudorandom and not random number generators. Assigning a seed to a generator you ensure that on consectuive runs the same results are returned. If you have written unit tests that assume values they will not break if you need to re-create the test data.

PHP's two pseudorandom random generators have constrains that need to be assumed, first being that the seed values are global and the second is that when the Soushin security extension is installed (most repo installs it comes by default) the generators are seeded and can not be changed. I have included a good replacement that implements the same algorithm that mt_rand uses the Mersenne Twiseter.

I have include 3 Generators that can be used

### SimpleRandom
Fast but has bad spread of random values, but will accept a seed.

```xml
<column name="a_column" generatorSeed="100" randomGenerator="simple" />
```

### SrandRandom
If you don't care and want to use php random it will accept a seed but if Soushin is installed it won't matter.

```xml
<column name="a_column" generatorSeed="100" randomGenerator="srand" />
```


### MersenneRandom
This one you should be using it will accept a seed and good spread of random values.

```xml
<column name="a_column" generatorSeed="100" randomGenerator="mersenne" />
```