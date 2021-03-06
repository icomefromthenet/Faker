<?php
namespace Faker\Components\Config\Driver\CLI;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Console\Helper\DialogHelper,
    Symfony\Component\Console\Output\OutputInterface,
    Faker\Components\Config\EntityInterface as Entity,
    Faker\Components\Config\InvalidConfigException,
    Faker\Components\Config\Driver\ConfigInterface,
    Faker\Components\Config\Exception as ConfigException;


class Sqlsrv implements ConfigInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        
        try {

            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('database');
    
            $rootNode
                ->children()
                ->scalarNode('type')->isRequired()
                    ->validate()
                        ->ifTrue(function($nodeValue) {
                            $nodeValue = strtolower($nodeValue);
                            if(in_array($nodeValue,array('pdo_sqlsrv')) === FALSE) {
                                return TRUE;
                            }
                            return FALSE;
                        })
                        ->then(function($value) {
                            throw new \RuntimeException('Database is not a valid type');
                        })
                    ->end()
                ->end()
                ->scalarNode('username')->isRequired()->end()
                ->scalarNode('password')->isRequired()->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('port')->defaultValue(3306)->end()
                ->scalarNode('schema')->isRequired()->end()
                ->end();

            } catch(\Exception $e) {
                throw new InvalidConfigException($e->getMessage());
            }
        
                
        return $treeBuilder;
    }

    //  -------------------------------------------------------------------------

    public function merge(Entity $entity,array $raw)
    {
        try {

            $processor = new Processor();
            $configuration = $this;
            $config = $processor->processConfiguration($configuration, array('database'=>$raw));
    
            $entity->setSchema($config['schema']);
            $entity->setUser($config['username']);
            $entity->setPassword($config['password']);
            $entity->setType($config['type']);
            $entity->setPort($config['port']);
            $entity->setHost($config['host']);
    
        } catch(\Exception $e) {
            throw new InvalidConfigException($e->getMessage());
        }
        
        return $entity;
    }
    
    
    //------------------------------------------------------------------

    public function interact(DialogHelper $dialog,OutputInterface $output,array $answers)
    {
        # Ask Database Schema Name
        $answers['schema'] =  $dialog->ask($output,'<question>What is the Database schema name? : </question>');

        #Database user Name
        $answers['username'] =  $dialog->ask($output,'<question>What is the Database user name? : </question>');

        #Database user Password
        $answers['password'] =  $dialog->ask($output,'<question>What is the Database users password? : </question>');

        #Database host
        $answers['host'] =  $dialog->ask($output,'<question>What is the Database host name? [localhost] : </question>','localhost');

        #Database port
        $answers['port'] =  $dialog->ask($output,'<question>What is the Database port? [3306] : </question>',3306);
        
        return $answers;
    }
   
   
    //------------------------------------------------------------------
   
    public function getName()
    {
        return 'sqlsrv';    
    }
   
    //------------------------------------------------------------------
}
/* End of File */