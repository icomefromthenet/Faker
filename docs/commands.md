#Faker Cli Commands

There are 4 commands that can be used.

## Init.

This is the first command that should be run, it will copy the skelton of a project into the current directory. The project folder must exists prior to running the init command. The location of the folder can be override with the -p option.

```
    mkdir database
    cd database
    ../vendor/bin/faker.php faker:init
```

Will setup a project under the projects/myproject/database/. 

## Configue.
This is the second command that should be run, it will ask a series of question about your database and create a config file, for connection to it. There is only one config file per project and running the command will overrite that file (will ask for confirmation first). Each platform has a custom set of questions, given different options supported by Doctrine DBAL.

```
    ../vendor/bin/faker.php faker:configure
```

Question will need to be answered.
```    
    Which Database does this belong? [mysql|mssql|oracle|posgsql]: mysql
    What is the Database schema name? : sakila
    What is the Database user name? : root
    etc ....
```
This will write a config file to config folder inside the project directory.

## Analyse.

This is a helpful method to get started with an existing schema, it will output a file called schema.xml to the sources directory. This schema will have all tables in the database listed as well as a default writter setup ready to use. The alphanumeric filler type will be applied to each column (alpahnumeric). 

```
   ../vendor/bin/faker.php faker:analyse
   
```

Don't run generate yet, open the schema file and fill in.

## Generate.

The last of the commands and the most important, once a schema is defined you will want to run this command to generate your test data.

```
    ../vendor/bin/faker.php faker:generate 
    ../vendor/bin/faker.php faker:generate other_schema.xml
```
uses schema.xml by default, different schema can be specified using the first argument.
