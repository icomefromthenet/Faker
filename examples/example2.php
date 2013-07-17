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
    
$builder = SchemaBuilder::create($container,$name,$locale,$util,$gen);
        
$composite = $builder
            ->describe()
                ->addTable('aa')
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
                ->addTable('table1')
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
                        ->singleFileMode(true)
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
// Return null as we using entity generator not PHP Builder Composite
//
//--------------------------------------------------------------------

return $composite;