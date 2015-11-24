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
                ->addTable('aa')
                    ->toGenerate(100)
                    ->addColumn('column1')
                        ->dbalType('string')
                        ->addField()
                            ->fieldCity()
                                ->countries(array('AU'))
                            ->endCityField()
                        ->endField()
                    ->endColumn()
                ->endTable()
                ->addTable('table1')
                    ->toGenerate(100)
                    ->addColumn('column2')
                        ->dbalType('string')
                        ->addField('column2')
                            ->fieldAlphaNumeric()
                                ->format('cccCCCCCCC')
                            ->endAlphaNumericField()
                            ->fieldConstant()
                                ->cast('string')
                                ->value("O'Brien")
                            ->endConstantField()
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
                ->sqlWriter()
                    ->usePlatform('sqlite')
                    ->singleFileMode(true)
                ->endSqlWriter()
            ->endWriter()
            ->addWriter()
                ->phpUnitWriter()
                    ->outputEncoding('utf8')
                        ->outFileFormat('{prefix}_{body}_{suffix}.{ext}')
                ->endPhpUnitWriter()
            ->endWriter()
        ->endSchema();
        
                
//-------------------------------------------------------------------
// Return container to process all builders
//
//--------------------------------------------------------------------

return $container;