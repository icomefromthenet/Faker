<?php
use Faker\Components\Engine\DB\Builder\SchemaBuilder;

//---------------------------------------------------------------
// Define the Composite
//
//--------------------------------------------------------------

$container  = $application;

$name            = 'test_db';
$locale          = $container->getLocaleFactory()->create('en');
$util            = $container->getEngineUtilities();
$gen             = $container->getDefaultRandom();
    
$builder = $container->addPass('Pass1',$name,$locale,$util,$gen)
            ->describe()
                ->addTable('table1')
                    ->toGenerate(100)
                    ->addColumn('column2')
                        ->dbalType('string')
                        ->addField('column2')
                            ->fieldAlphaNumeric()
                                ->format('cccCCCCCCC')
                            ->endAlphaNumericField()
                        ->endField()
                    ->endColumn()
                    ->addColumn('column1')
                        ->dbalType('string')
                        ->addField()
                            ->fieldAutoIncrement()
                                ->incrementByValue(1)
                                ->startAtValue(1)
                            ->endAutoIncrementField()
                        ->endField()
                    ->endColumn()
                ->endTable()
            ->endDescribe()
            ->addWriter()
                ->sqlWritter()
                    ->singleFileMode(true)
                ->endSqlWriter()
            ->endWriter()
            ->addDatasource()
                ->createPHPSource()
                     ->setDataFromClosure(function(){
                         return new \ArrayIterator(array('a','b','c'));
                     })
                     ->setDatasourceName('examplesourceA')
                ->endPHPSource()
            ->endDatasource()
        ->endSchema();
        
                
//-------------------------------------------------------------------
// Return container to process all builders
//
//--------------------------------------------------------------------

return $container;