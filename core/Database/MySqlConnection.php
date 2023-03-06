<?php

namespace AwesomeCoder\Database;

use AwesomeCoder\Database\PDO\MySqlDriver;
use AwesomeCoder\Database\Query\Grammars\MySqlGrammar as QueryGrammar;
use AwesomeCoder\Database\Query\Processors\MySqlProcessor;
use AwesomeCoder\Database\Schema\Grammars\MySqlGrammar as SchemaGrammar;
use AwesomeCoder\Database\Schema\MySqlBuilder;
use AwesomeCoder\Database\Schema\MySqlSchemaState;
use AwesomeCoder\Filesystem\Filesystem;
use PDO;

class MySqlConnection extends Connection
{
    /**
     * Determine if the connected database is a MariaDB database.
     *
     * @return bool
     */
    public function isMaria()
    {
        return str_contains($this->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), 'MariaDB');
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \AwesomeCoder\Database\Query\Grammars\MySqlGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \AwesomeCoder\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new MySqlBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \AwesomeCoder\Database\Schema\Grammars\MySqlGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get the schema state for the connection.
     *
     * @param  \AwesomeCoder\Filesystem\Filesystem|null  $files
     * @param  callable|null  $processFactory
     * @return \AwesomeCoder\Database\Schema\MySqlSchemaState
     */
    public function getSchemaState(Filesystem $files = null, callable $processFactory = null)
    {
        return new MySqlSchemaState($this, $files, $processFactory);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \AwesomeCoder\Database\Query\Processors\MySqlProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new MySqlProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \AwesomeCoder\Database\PDO\MySqlDriver
     */
    protected function getDoctrineDriver()
    {
        return new MySqlDriver;
    }
}
