<?php
use Faker\Components\Engine\Entity\Builder\EntityGenerator;
use Faker\Components\Engine\Entity\GenericEntity;

//---------------------------------------------------------------
// Define the Entity
//
//--------------------------------------------------------------

$container  = $application;
$name       = 'example1';
$locale     = $container->getLocaleFactory()->create('en');
$util       = $container->getEngineUtilities();
$gen        = $container->getDefaultRandom();
    
$builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
$entityIterator = $builder
                ->describe()
                    ->addField('myfield')
                        ->fieldAutoIncrement()
                            ->startAtValue(5)
                            ->incrementByValue(1)
                        ->end()
                    ->end()
                    ->addField('otherfield')
                        ->fieldAlphaNumeric()
                            ->format('cccc')
                        ->end()
                    ->end()
                ->end()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(1);
                
//---------------------------------------------------------------
// Output the Entity
//
//--------------------------------------------------------------

                
foreach($entityIterator as $result) {
          echo $result->myfield . PHP_EOL; 
}

//-------------------------------------------------------------------
// Return null as we using entity generator not PHP Builder Composite
//
//--------------------------------------------------------------------

return null;