<?php
namespace Faker\Components\Config;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface,
    Symfony\Component\Config\Definition\Processor,
    Faker\Components\Config\InvalidConfigurationException;

class Entity implements ConfigurationInterface
{


    //  -------------------------------------------------------------------------

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
                    ->scalarNode('db_type')->isRequired()
                        ->validate()
                            ->ifTrue(function($nodeValue) {
                                $nodeValue = strtolower($nodeValue);
                                if(in_array($nodeValue,array('pdo_mysql','pdo_sqlite','pdo_pgsql','pdo_sqlsrv','pdo_oci','oci8')) === FALSE) {
                                    return TRUE;
                                }
                                return FALSE;
                            })
                            ->then(function($value) {
                                throw new \RuntimeException('Database is not a valid type');
                            })
                        ->end()
                    ->end()
                    ->scalarNode('db_schema')->isRequired()->end()
                    ->scalarNode('db_user')->isRequired()->end()
                    ->scalarNode('db_password')->isRequired()->end()
                    ->scalarNode('db_host')->defaultValue('localhost')->end()
                    ->scalarNode('db_port')->defaultValue(3306)->end()
                    ->end();
    
            return $treeBuilder;
    
        } catch(\Exception $e) {
            throw new InvalidConfigException($e->getMessage());
        }
    }

    //  -------------------------------------------------------------------------

    /**
     * Merges the config array with the config tree
     *
     * @param array $configs
     * @return boolean true if merge sucessful
     */
    public function merge(array $config)
    {
        try {
        
            $processor = new Processor();
            $configuration = $this;
            $config = $processor->processConfiguration($configuration, array('database'=>$config));
    
            $this->dbhost = $config['db_host'];
            $this->dbport = $config['db_port'];
            $this->dbschema = $config['db_schema'];
            $this->dbtype = $config['db_type'];
            $this->dbuser = $config['db_user'];
            $this->dbpassword = $config['db_password'];
            
            return true;
        
        } catch(\Exception $e) {
            throw new InvalidConfigException($e->getMessage());
        }
    }


    //  ----------------------------------------------------------------
    # Properties

    /**
      *  @var string the database schema name
      */
    protected $dbschema;

    public function getSchema()
    {
        return $this->dbschema;
    }

    //------------------------------------------------------------------------



    /**
      * @var string the database schema username
      */
    protected $dbuser;

    public function getUser()
    {
        return $this->dbuser;
    }

    //------------------------------------------------------------------------



    /**
      * @var string database type
      */
    protected $dbtype;

    public function getType()
    {
        return $this->dbtype;
    }

    //------------------------------------------------------------------------



    /**
      * @var integer the database connection port
      */
    protected $dbport;

    public function getPort()
    {
        return $this->dbport;
    }

    //------------------------------------------------------------------------


    /**
      * @var the host name or ip
      */
    protected $dbhost;

    public function getHost()
    {
        return $this->dbhost;
    }



    //------------------------------------------------------------------------
    /**
      *  @ar string the database password
      */
    protected $dbpassword;

    public function getPassword()
    {
        return $this->dbpassword;
    }


    //------------------------------------------------------------------------
}
/* End of File */
