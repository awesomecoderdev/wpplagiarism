<?php

namespace AwesomeCoder\Database;

use AwesomeCoder\Database\PDO\PostgresDriver;
use AwesomeCoder\Database\Query\Grammars\PostgresGrammar as QueryGrammar;
use AwesomeCoder\Database\Query\Processors\PostgresProcessor;
use AwesomeCoder\Database\Schema\Grammars\PostgresGrammar as SchemaGrammar;
use AwesomeCoder\Database\Schema\PostgresBuilder;
use AwesomeCoder\Database\Schema\PostgresSchemaState;
use AwesomeCoder\Filesystem\Filesystem;

class PostgresConnection extends Connection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \AwesomeCoder\Database\Query\Grammars\PostgresGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \AwesomeCoder\Database\Schema\PostgresBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new PostgresBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \AwesomeCoder\Database\Schema\Grammars\PostgresGrammar
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
     * @return \AwesomeCoder\Database\Schema\PostgresSchemaState
     */
    public function getSchemaState(Filesystem $files = null, callable $processFactory = null)
    {
        return new PostgresSchemaState($this, $files, $processFactory);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \AwesomeCoder\Database\Query\Processors\PostgresProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new PostgresProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \AwesomeCoder\Database\PDO\PostgresDriver
     */
    protected function getDoctrineDriver()
    {
        return new PostgresDriver;
    }
}
