<?php
namespace Faker\Tests\Engine\Entity;

use Faker\Components\Engine\Entity\Builder\EntityGenerator;
use Faker\Components\Engine\Entity\GenericEntity;
use Faker\Tests\Base\AbstractProject;

class BuilderExamplesTest extends AbstractProject
{
    
    
    
    public function testEntityGenDescribeReturnsBuilder()
    {
        $container       = $this->getProject();
        $fieldCollection = EntityGenerator::create($container,'example1')->describe();
        
        $this->assertInstanceOF('Faker\Components\Engine\Entity\Builder\FieldCollection',$fieldCollection);
    }
    
    
    public function testExample1()
    {
        $container  = $this->getProject();
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
                        ->endAutoIncrementField()
                    ->endField()
                    ->addField('otherfield')
                        ->fieldAlphaNumeric()
                            ->format('cccc')
                        ->endAlphaNumericField()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(1);
        
        foreach($entityIterator as $result) {
            $this->assertInternalType('integer', $result->myfield);
            $this->assertInternalType('string' , $result->otherfield);
        }
        
    }
    
    public function testExample2()
    {
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();
    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $nowDate = new \DateTime();
        $maxDate = clone $nowDate;
        $maxDate->modify('+ 100 days');
        
        $entityIterator = $builder
                ->describe()
                    ->addField('autoIncrementField')
                        ->fieldAutoIncrement()
                            ->startAtValue(5)
                            ->incrementByValue(1)
                        ->endAutoIncrementfield()
                    ->endField()
                    ->addField('alphaField')
                        ->fieldAlphaNumeric()
                            ->format('cccc')
                        ->endAlphaNumericField()
                    ->endField()
                    ->addField('booleanField')
                        ->fieldBoolean()
                            ->value(true)
                        ->endBooleanField()
                    ->endField()
                    ->addField('cityField')
                        ->fieldCity()
                            ->countries(array('AU'))
                        ->endCityField()
                    ->endField()
                    ->addField('closureField')
                        ->fieldClosure()
                            ->execute(function($rows,GenericEntity $ent){
                            return  'closure result';
                        })
                        ->endClosureField()
                    ->endField()
                    ->addField('fieldConstant')
                        ->fieldConstant()
                            ->value(100)
                            ->cast('float')
                        ->endConstantField()
                    ->endField()
                    ->addField('fieldCountry')
                        ->fieldCountry()                    
                        ->endCountryField()    
                    ->endField()
                    ->addField('fieldDate')
                        ->fieldDate()
                            ->startDate($nowDate)
                            ->maxDate($maxDate)
                            ->modifyTime('+ 1 day')
                            ->pickRandomBetweenMinMax()
                        ->endDateField()
                    ->endField()
                    ->addField('fieldEmail')
                        ->fieldEmail()
                            ->format('{fname}{lname}{alpha}@.{domain}')
                            ->params(array('alpha' => 'ccccXX'))
                        ->endEmailField()
                    ->endField()
                    ->addField('fieldPeople')
                        ->fieldPeople()
                            ->format('{fname} {inital} {lname}')
                        ->endPeopleField()
                    ->endField()
                    ->addField('fieldNull')
                        ->fieldNull()
                        ->endNullField()
                    ->endField()
                    ->addField('fieldNumeric')
                        ->fieldNumeric()
                            ->format('xxxx.xx')
                        ->endNumericField()
                    ->endField()
                    ->addField('fieldRange')
                        ->fieldRange()
                            ->startAtValue(10.456)
                            ->stopAtValue(90)
                            ->incrementByValue(3.5678)
                            ->incrementWindow(2)
                            ->roundToXDecimals(2)
                        ->endRangeField()
                    ->endField()
                    ->addField('fieldRegex')
                        ->fieldRegex()
                            ->regex('[a-k0-8]{50,100}')
                        ->endRegexField()
                    ->endField()
                    ->addField('fieldUniqueNumber')
                        ->fieldUniqueNumber()
                            ->format('xxxx.xxx')
                        ->endUniqueNumberField()
                    ->endField()
                    ->addField('fieldUniqueString')
                        ->fieldUniqueString()
                            ->format('ccc-ccc-ccc-ccc-cccc')
                        ->endUniqueStringField()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(1);
        
        foreach($entityIterator as $result) {
            $this->assertInternalType('integer', $result->autoIncrementField);
            $this->assertInternalType('string' , $result->alphaField);
            $this->assertInternalType('boolean' , $result->booleanField);
            $this->assertInternalType('string',$result->cityField);
            $this->assertEquals('closure result',$result->closureField);
            $this->assertEquals(100.00,$result->fieldConstant);
            $this->assertInternalType('float',$result->fieldConstant);
            $this->assertInternalType('string',$result->fieldCountry);
            $this->assertInstanceOf('DateTime',$result->fieldDate);
            $this->assertInternalType('string',$result->fieldEmail);
            $this->assertInternalType('string',$result->fieldPeople);
            $this->assertInternalType('null',$result->fieldNull);
            $this->assertInternalType('float',$result->fieldNumeric);
            $this->assertInternalType('float',$result->fieldRange);
            $this->assertInternalType('string',$result->fieldRegex);
            $this->assertInternalType('string',$result->fieldUniqueString);
            $this->assertTrue(is_numeric($result->fieldUniqueNumber));
        }
        
    }
    
    
    public function testExample3()
    {
        
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();

    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $entityIterator = $builder
                ->describe()
                    ->addField('myField')
                        ->combination()
                            ->fieldAutoIncrement()
                                ->startAtValue(5)
                                ->incrementByValue(1)
                            ->endAutoIncrementField()
                            ->fieldAlphaNumeric()
                                ->format('cccc')
                            ->endAlphaNumericField()
                        ->endCombination()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(10);
        
        foreach($entityIterator as $result)
        {
            $this->assertRegExp('/^[0-9]+[a-zA-Z]{4}$/',$result->myField);
        }
       
    }
    
    
    public function testExample4()
    {
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();

    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $entityIterator = $builder
                ->describe()
                    ->addField('myField')
                        ->selectorRandom()
                            ->describe()
                                ->fieldAutoIncrement()
                                    ->startAtValue(5)
                                    ->incrementByValue(1)
                                ->endAutoIncrementField()
                                ->fieldAlphaNumeric()
                                    ->format('cccc')
                                ->endAlphaNumericField()
                            ->endDescribe()
                        ->endRandomSelector()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(10);
        
        foreach($entityIterator as $result) {
            $this->assertRegExp('/[0-9]*[a-zA-Z]{0,4}/',(string)$result->myField);
        }
        
        
    }
    
    
    public function testExample5()
    {
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();

    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $entityIterator = $builder
                ->describe()
                    ->addField('myField')
                        ->selectorWeight()
                            ->weight(0.3)
                            ->describe()
                                ->fieldAutoIncrement()
                                    ->startAtValue(5)
                                    ->incrementByValue(1)
                                ->endAutoIncrementField()
                                ->fieldAlphaNumeric()
                                    ->format('cccc')
                                ->endAlphaNumericField()
                            ->endDescribe()
                        ->endWeightSelector()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(10);
        
        foreach($entityIterator as $result) {
            $this->assertRegExp('/[0-9]*[a-zA-Z]{0,4}/',(string)$result->myField);
        }
        
    }
    
    public function testExample6()
    {
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();

    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $entityIterator = $builder
                ->describe()
                    ->addField('myField')
                        ->selectorAlternate()
                            ->step(3)
                            ->describe()
                                ->fieldAutoIncrement()
                                    ->startAtValue(5)
                                    ->incrementByValue(1)
                                ->endAutoIncrementField()
                                ->fieldAlphaNumeric()
                                    ->format('cccc')
                                ->endAlphaNumericField()
                            ->endDescribe()
                        ->endAlternateSelector()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(10);
        
        foreach($entityIterator as $result) {
            $this->assertRegExp('/[0-9]*[a-zA-Z]{0,4}/',(string)$result->myField);
        }
        
    }
    
    public function testExample7()
    {
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();

    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $entityIterator = $builder
                ->describe()
                    ->addField('myField')
                        ->selectorSwap()
                            ->swapAt(2)
                                ->fieldAutoIncrement()
                                    ->startAtValue(5)
                                    ->incrementByValue(1)
                                ->endAutoIncrementField()
                            ->endSwapAt()
                            ->swapAt(5)
                                ->fieldAlphaNumeric()
                                    ->format('cccc')
                                ->endAlphaNumericField()
                            ->endSwapAt()
                        ->endSwapSelector()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(20);
        
        foreach($entityIterator as $result) {
            $this->assertRegExp('/[0-9]*[a-zA-Z]{0,4}/',(string)$result->myField);
        }
        
    }
    
    public function testExample8()
    {
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();

    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $entityIterator = $builder
                ->describe()
                    ->addField('myField')
                        ->selectorSwap()
                            ->swapAt(2)
                                ->selectorRandom()
                                    ->describe()
                                        ->fieldAutoIncrement()
                                            ->startAtValue(5)
                                            ->incrementByValue(1)
                                        ->endAutoIncrementField()
                                        ->fieldAutoIncrement()
                                            ->startAtValue(10)
                                            ->incrementByValue(1)
                                        ->endAutoIncrementField()                    
                                    ->endDescribe()
                                ->endRandomSelector()
                            ->endSwapAt()
                            ->swapAt(5)
                                ->fieldAlphaNumeric()
                                    ->format('cccc')
                                ->endAlphaNumericField()
                            ->endSwapAt()
                        ->endSwapSelector()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(20);
        
        foreach($entityIterator as $result) {
            $this->assertRegExp('/[0-9]*[a-zA-Z]{0,4}/',(string)$result->myField);
        }
        
    }
    
    
    public function testExample9()
    {
        $container  = $this->getProject();
        $name       = 'example1';
        $locale     = $container->getLocaleFactory()->create('en');
        $util       = $container->getEngineUtilities();
        $gen        = $container->getDefaultRandom();
    
        $builder    =  EntityGenerator::create($container,$name,$locale,$util,$gen);
        
        $entityIterator = $builder
                ->describe()
                    ->addField('myField')
                        ->selectorSwap()
                            ->swapAt(2)
                                ->selectorRandom()
                                    ->describe()
                                        ->fieldAutoIncrement()
                                            ->startAtValue(5)
                                            ->incrementByValue(1)
                                        ->endAutoIncrementField()
                                        ->combination()
                                            ->fieldAutoIncrement()
                                                ->startAtValue(10)
                                                ->incrementByValue(1)
                                            ->endAutoIncrementField()
                                            ->fieldConstant()
                                                ->value('_')
                                                ->cast('string')
                                            ->endConstantField()
                                            ->fieldRegex()
                                                ->regex('[a-zA-Z]{5,10}')
                                            ->endRegexField()
                                        ->endCombination()
                                    ->endDescribe()
                                ->endRandomSelector()
                            ->endSwapAt()
                            ->swapAt(5)
                                ->fieldAlphaNumeric()
                                    ->format('cccc')
                                ->endAlphaNumericField()
                            ->endSwapAt()
                        ->endSwapSelector()
                    ->endField()
                ->endDescribe()
                ->map(function (GenericEntity $entity) {
                    return $entity;
                })
                ->fake(20);
        
        
        foreach($entityIterator as $result) {
            $this->assertRegExp('/[0-9]*[a-zA-Z_]{0,10}/',(string)$result->myField);
        }
        
    }
    
    
}
/* End of Class */