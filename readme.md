#Faker - A Database testing tool.

There have been a few ports of the original [Perl Faker](http://search.cpan.org/~wsheldahl/Data-Faker-0.09/lib/Data/Faker.pm) and a few to php. What started as another has grown instead into a comprehensive data generation tool, powered by Doctrine DBAL and Symfony2 Components.

Where I think Faker will help you:
 1. Write PHPUnit DBUnit fixtures, this will save you time (especially if that involves more than one platform).
 2. You need to fill database with test data for various loading test.

If your a database tester or a lone developer Faker will help you test your database quicker.

##Features

 1. Supports multiple platforms using Doctrine DBAL. (MySql|PGSQL|Oracle|Sqlite|MSSql).
 2. Supports PHPUnit Dataset XML for DBUnit fixtures.
 3. Supports Standard Sql DDL (insert statements) for the platforms mentioned above.
 5. Output Formats have their own templates that can be customized per project. (add own branding).
 4. Configured via XML file that feels closer to your database.
 5. Many [built in datatypes](http://github.com/icomefromthenet/Faker/blob/master/docs/types/index.md), Including text, numeric , autoincrements, email , city names etc
 6. Embraces extension, write own types, writters and locales.
 7. Installed via composer.
 8. Analyse and build struct from existing database.
 9. Seed your random number generator for repeatable results (seed global|table|column|type). 
 10. Supported custom locals, generate test data in unicode. **(Not just ENGLISH)**.
 11. Project folder that can be version controled with your project.

##Requirements.

SQlite3, PHP5.3.3, MBString, Pear and Linux / Mac (windows support in beta).

All components are included by default in package installs of php.

## How to install
The easist way to install to use composer

```json
{
  "require-dev" : {
    "icomefromthenet/migration": "dev-master"
  }
}
```

Read more from the [starting guide](http://github.com/icomefromthenet/Faker/blob/master/docs/starting.md)

## Quick Example - still wondering

**Turn your SCHEMA**

```xml

<?xml version="1.0"?>
<schema name="sakila" randomGenerator="simple" generatorSeed="1000">
 <writer platform="mysql" format="sql" singleFileMode="true" />
 
 <table name="actor" generate="100" randomGenerator="simple" generatorSeed="1000">
  <column name="actor_id" type="integer" randomGenerator="simple" generatorSeed="1000">
   <datatype name="autoincrement">
     <option name="start" value="1" />
     <option name="randomGenerator" value="simple" />
     <option name="generatorSeed"   value="100" />
   </datatype>
  </column>
  <column name="first_name" type="string">
   <datatype name="people">
    <option name="format" value="{fname}" />
   </datatype>
  </column>
  <column name="last_name" type="string">
   <datatype name="people">
    <option name="format" value="{lname}" />
   </datatype>
  </column>
  <column name="last_update" type="datetime">
   <datatype name="date">
     <option name="start" value="today" />
     <option name="modify" value="+1 week" />
     <option name="max" value="today +10 weeks" />
   </datatype>
  </column>
 </table>
 
 <table name="actor_homes" generate="10000">
  <column name="actor_id" type="integer">
   <foreign-key name="actor_homes.actor_id" foreignTable="actor" foreignColumn="actor_id" />
  </column>
 </table>
 
</schema>

```

**Calling command**

``` bash
 ../vendor/bin/faker.php faker:generate schema.xml

```


**INTO DATA**

```sql
-- Migrations Faker Dump
-- version 1.0.2
-- https://github.com/icomefromthenet/Faker
--
-- Host: localhost
-- Generation Time: 2012-07-03T06:08:02+00:00
-- PHP Version: 5.3.10-1ubuntu3.2
-- Platform: mysql

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: sakila
--

-- --------------------------------------------------------



--
-- Table: actor
--


USE sakila;

INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (1,'Jordan','Proctor','2012-07-03 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (2,'Michele','Welsh','2012-07-10 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (3,'Paul','Lam','2012-07-17 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (4,'Sheila','Best','2012-07-24 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (5,'Karl','Moser','2012-07-31 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (6,'Victoria','Todd','2012-08-07 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (7,'Julia','Sun','2012-08-14 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (8,'Jimmy','Rankin','2012-08-21 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (9,'George','Brantley','2012-08-28 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (10,'Wendy','Walton','2012-09-04 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (11,'Lester','Osborne','2012-09-11 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (12,'Edwin','Upchurch','2012-07-03 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (13,'Courtney','Ennis','2012-07-10 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (14,'Nina','Fischer','2012-07-17 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (15,'Brooke','Benson','2012-07-24 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (16,'Angela','Rodriguez','2012-07-31 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (17,'Todd','Wiggins','2012-08-07 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (18,'Ron','Smith','2012-08-14 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (19,'Andrew','Coates','2012-08-21 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (20,'Kerry','Harrison','2012-08-28 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (21,'Harvey','Mercer','2012-09-04 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (22,'Herbert','Gould','2012-09-11 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (23,'Donna','Forrest','2012-07-03 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (24,'Vivian','Sumner','2012-07-10 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (25,'Carol','Oliver','2012-07-17 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (26,'Patricia','Tate','2012-07-24 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (27,'Dolores','Bowers','2012-07-31 00:00:00');
INSERT INTO `actor` (`actor_id`,`first_name`,`last_name`,`last_update`) VALUES (28,'Gerald','Pearson','2012-08-07 00:00:00');

```
