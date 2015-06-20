<?php
use Faker\Components\Engine\DB\Builder\SchemaBuilder;
use Faker\Components\Config\Entity;

//---------------------------------------------------------------
// Define the Composite with a database connection
// Where no using config file here, manually create the config entity
//--------------------------------------------------------------

$container  = $application;

$name            = 'test_db';
$locale          = $container->getLocaleFactory()->create('en');
$util            = $container->getEngineUtilities();
$gen             = $container->getDefaultRandom();
$connectPool     = $container->getConnectionPool();


$entity = new Entity();
        
$entity->setType('pdo_sqlite');
$entity->setMemory(true);
$connectPool->addExtraConnection('testConnect',$entity);
    
$builder = $container
            ->onGlobalGenerateStart(function()use($container){
                $conn = $container->getConnectionPool()->getExtraConnection('testConnect');
                
                # ddl build schema
                $sSql  = 'CREATE TABLE people_names(column1 NUMERIC);';
                $sSql .= 'CREATE TABLE people_codes(column1 NUMERIC,column2 NUMERIC);';         
                    
                $conn->exec($sSql);    
                
            })
            ->onGlobalGenerateEnd(function()use($container){
                $conn = $container->getConnectionPool()->getExtraConnection('testConnect');
                
                # query values to test successful insert
                //var_dump($conn->fetchAll('SELECT * FROM people_names'));
            })
            ->addPass('FirstPass',$name,$locale,$util,$gen)
            ->describe()
                ->addTable('people_names')
                    ->toGenerate(100)
                    ->addColumn('column1')
                        ->dbalType('string')
                        ->addField()
                            ->fieldCity()
                                ->countries(array('AU'))
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->addTable('people_codes')
                    ->toGenerate(100)
                    ->addColumn('column2')
                        ->dbalType('string')
                        ->addField('column2')
                            ->fieldAlphaNumeric()
                                ->format('cccCCCCCCC')
                            ->end()
                        ->end()
                    ->end()
                    ->addColumn('column1')
                        ->dbalType('string')
                        ->addField()
                            ->fieldAutoIncrement()
                                ->incrementByValue(1)
                                    ->startAtValue(1)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->addWriter()
                    ->sqlWritter()
                        ->writeToDatabase('testConnect')
                        ->usePlatform('sqlite')
                    ->end()
                ->end()
                ->addWriter()
                    ->phpUnitWritter()
                        ->outputEncoding('utf8')
                            ->outFileFormat('{prefix}_{body}_{suffix}.{ext}')
                    ->end()
                ->end()
            ->end();
        
                
//-------------------------------------------------------------------
// Return container to process all builders
//
//--------------------------------------------------------------------

return $container;