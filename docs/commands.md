#Faker Cli Commands

There are 4 commands that can be used.

## Init.

This is the first command that should be run, it will copy the skelton of a project into the current directory. The project folder must exists prior to running the init command. The location of the folder can be override with the -p option.


    projects/myproject$ mkdir database
    projects/myproject$ cd database
    projects/myproject/database$ faker init


Will setup a project under the /home/lewis/projects/myproject/database/ folder but

    myproject/database$ mkdir myfaker
    myproject/database$ faker init -p myfaker

Will setup a project folder under /home/lewis/projects/myproject/database/myfaker , in both cases the folder must exist first.

## Configue.
This is the second command that should be run, it will ask a series of question about your database and create a config file, for connection to it. There is only one config file per project and running the command will overrite that file (will ask for confirmation first).


    myproject/database faker configure
    Which Database does this belong? [mysql|mssql|oracle|posgsql]: mysql
    What is the Database schema name? : sakila
    What is the Database user name? : root
    What is the Database users password? : vagrant
    What is the Database host name? [localhost] : localhost
    What is the Database port? [3306]: 3306


Will write a config file to config folder inside the project directory.

## Analyse.

This is a helpful method to get started with an existing schema, it will output a file called schema.xml to the sources directory under the project. This schema will have all tables in the database listed as well as a default writter setup ready to use. A filler type will be applied to each column (alpahnumeric). 


    myproject/database faker analyse

Will output schema.xml to sources directory under the project. 

## Generate.

The last of the commands and the most important, once a schema is defined you will want to run this command to generate your test data.


    myproject/database faker generate


