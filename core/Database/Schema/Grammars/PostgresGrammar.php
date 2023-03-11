<?php

namespace AwesomeCoder\Database\Schema\Grammars;

use AwesomeCoder\Database\Connection;
use AwesomeCoder\Database\Schema\Blueprint;
use AwesomeCoder\Support\Fluent;

class PostgresGrammar extends Grammar
{
    /**
     * If this Grammar supports schema changes wrapped in a transaction.
     *
     * @var bool
     */
    protected $transactions = true;

    /**
     * The possible column modifiers.
     *
     * @var string[]
     */
    protected $modifiers = ['Collate', 'Increment', 'Nullable', 'Default', 'VirtualAs', 'StoredAs'];

    /**
     * The columns available as serials.
     *
     * @var string[]
     */
    protected $serials = ['bigInteger', 'integer', 'mediumInteger', 'smallInteger', 'tinyInteger'];

    /**
     * The commands to be executed outside of create or alter command.
     *
     * @var string[]
     */
    protected $fluentCommands = ['Comment'];

    /**
     * Compile a create database command.
     *
     * @param  string  $name
     * @param  \AwesomeCoder\Database\Connection  $connection
     * @return string
     */
    public function compileCreateDatabase($name, $connection)
    {
        return sprintf(
            'create database %s encoding %s',
            $this->wrapValue($name),
            $this->wrapValue($connection->getConfig('charset')),
        );
    }

    /**
     * Compile a drop database if exists command.
     *
     * @param  string  $name
     * @return string
     */
    public function compileDropDatabaseIfExists($name)
    {
        return sprintf(
            'drop database if exists %s',
            $this->wrapValue($name)
        );
    }

    /**
     * Compile the query to determine if a table exists.
     *
     * @return string
     */
    public function compileTableExists()
    {
        return "select * from information_schema.tables where table_catalog = ? and table_schema = ? and table_name = ? and table_type = 'BASE TABLE'";
    }

    /**
     * Compile the query to determine the list of columns.
     *
     * @return string
     */
    public function compileColumnListing()
    {
        return 'select column_name from information_schema.columns where table_catalog = ? and table_schema = ? and table_name = ?';
    }

    /**
     * Compile a create table command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return array
     */
    public function compileCreate(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf(
            '%s table %s (%s)',
            $blueprint->temporary ? 'create temporary' : 'create',
            $this->wrapTable($blueprint),
            implode(', ', $this->getColumns($blueprint))
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }

    /**
     * Compile a column addition command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileAdd(Blueprint $blueprint, Fluent $command)
    {
        return array_values(array_filter(array_merge([sprintf(
            'alter table %s %s',
            $this->wrapTable($blueprint),
            implode(', ', $this->prefixArray('add column', $this->getColumns($blueprint)))
        )], $this->compileAutoIncrementStartingValues($blueprint))));
    }

    /**
     * Compile the auto-incrementing column starting values.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @return array
     */
    public function compileAutoIncrementStartingValues(Blueprint $blueprint)
    {
        return collect($blueprint->autoIncrementingStartingValues())->map(function ($value, $column) use ($blueprint) {
            return 'alter sequence ' . $blueprint->getTable() . '_' . $column . '_seq restart with ' . $value;
        })->all();
    }

    /**
     * Compile a rename column command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @param  \AwesomeCoder\Database\Connection  $connection
     * @return array|string
     */
    public function compileRenameColumn(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        return $connection->usingNativeSchemaOperations()
            ? sprintf(
                'alter table %s rename column %s to %s',
                $this->wrapTable($blueprint),
                $this->wrap($command->from),
                $this->wrap($command->to)
            )
            : parent::compileRenameColumn($blueprint, $command, $connection);
    }

    /**
     * Compile a primary key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compilePrimary(Blueprint $blueprint, Fluent $command)
    {
        $columns = $this->columnize($command->columns);

        return 'alter table ' . $this->wrapTable($blueprint) . " add primary key ({$columns})";
    }

    /**
     * Compile a unique key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileUnique(Blueprint $blueprint, Fluent $command)
    {
        $sql = sprintf(
            'alter table %s add constraint %s unique (%s)',
            $this->wrapTable($blueprint),
            $this->wrap($command->index),
            $this->columnize($command->columns)
        );

        if (!is_null($command->deferrable)) {
            $sql .= $command->deferrable ? ' deferrable' : ' not deferrable';
        }

        if ($command->deferrable && !is_null($command->initiallyImmediate)) {
            $sql .= $command->initiallyImmediate ? ' initially immediate' : ' initially deferred';
        }

        return $sql;
    }

    /**
     * Compile a plain index key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileIndex(Blueprint $blueprint, Fluent $command)
    {
        return sprintf(
            'create index %s on %s%s (%s)',
            $this->wrap($command->index),
            $this->wrapTable($blueprint),
            $command->algorithm ? ' using ' . $command->algorithm : '',
            $this->columnize($command->columns)
        );
    }

    /**
     * Compile a fulltext index key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     *
     * @throws \RuntimeException
     */
    public function compileFulltext(Blueprint $blueprint, Fluent $command)
    {
        $language = $command->language ?: 'english';

        $columns = array_map(function ($column) use ($language) {
            return "to_tsvector({$this->quoteString($language)}, {$this->wrap($column)})";
        }, $command->columns);

        return sprintf(
            'create index %s on %s using gin ((%s))',
            $this->wrap($command->index),
            $this->wrapTable($blueprint),
            implode(' || ', $columns)
        );
    }

    /**
     * Compile a spatial index key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileSpatialIndex(Blueprint $blueprint, Fluent $command)
    {
        $command->algorithm = 'gist';

        return $this->compileIndex($blueprint, $command);
    }

    /**
     * Compile a foreign key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileForeign(Blueprint $blueprint, Fluent $command)
    {
        $sql = parent::compileForeign($blueprint, $command);

        if (!is_null($command->deferrable)) {
            $sql .= $command->deferrable ? ' deferrable' : ' not deferrable';
        }

        if ($command->deferrable && !is_null($command->initiallyImmediate)) {
            $sql .= $command->initiallyImmediate ? ' initially immediate' : ' initially deferred';
        }

        if (!is_null($command->notValid)) {
            $sql .= ' not valid';
        }

        return $sql;
    }

    /**
     * Compile a drop table command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDrop(Blueprint $blueprint, Fluent $command)
    {
        return 'drop table ' . $this->wrapTable($blueprint);
    }

    /**
     * Compile a drop table (if exists) command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropIfExists(Blueprint $blueprint, Fluent $command)
    {
        return 'drop table if exists ' . $this->wrapTable($blueprint);
    }

    /**
     * Compile the SQL needed to drop all tables.
     *
     * @param  array  $tables
     * @return string
     */
    public function compileDropAllTables($tables)
    {
        return 'drop table ' . implode(',', $this->escapeNames($tables)) . ' cascade';
    }

    /**
     * Compile the SQL needed to drop all views.
     *
     * @param  array  $views
     * @return string
     */
    public function compileDropAllViews($views)
    {
        return 'drop view ' . implode(',', $this->escapeNames($views)) . ' cascade';
    }

    /**
     * Compile the SQL needed to drop all types.
     *
     * @param  array  $types
     * @return string
     */
    public function compileDropAllTypes($types)
    {
        return 'drop type ' . implode(',', $this->escapeNames($types)) . ' cascade';
    }

    /**
     * Compile the SQL needed to retrieve all table names.
     *
     * @param  string|array  $searchPath
     * @return string
     */
    public function compileGetAllTables($searchPath)
    {
        return "select tablename, concat('\"', schemaname, '\".\"', tablename, '\"') as qualifiedname from pg_catalog.pg_tables where schemaname in ('" . implode("','", (array) $searchPath) . "')";
    }

    /**
     * Compile the SQL needed to retrieve all view names.
     *
     * @param  string|array  $searchPath
     * @return string
     */
    public function compileGetAllViews($searchPath)
    {
        return "select viewname, concat('\"', schemaname, '\".\"', viewname, '\"') as qualifiedname from pg_catalog.pg_views where schemaname in ('" . implode("','", (array) $searchPath) . "')";
    }

    /**
     * Compile the SQL needed to retrieve all type names.
     *
     * @return string
     */
    public function compileGetAllTypes()
    {
        return 'select distinct pg_type.typname from pg_type inner join pg_enum on pg_enum.enumtypid = pg_type.oid';
    }

    /**
     * Compile a drop column command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropColumn(Blueprint $blueprint, Fluent $command)
    {
        $columns = $this->prefixArray('drop column', $this->wrapArray($command->columns));

        return 'alter table ' . $this->wrapTable($blueprint) . ' ' . implode(', ', $columns);
    }

    /**
     * Compile a drop primary key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropPrimary(Blueprint $blueprint, Fluent $command)
    {
        $index = $this->wrap("{$blueprint->getTable()}_pkey");

        return 'alter table ' . $this->wrapTable($blueprint) . " drop constraint {$index}";
    }

    /**
     * Compile a drop unique key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropUnique(Blueprint $blueprint, Fluent $command)
    {
        $index = $this->wrap($command->index);

        return "alter table {$this->wrapTable($blueprint)} drop constraint {$index}";
    }

    /**
     * Compile a drop index command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropIndex(Blueprint $blueprint, Fluent $command)
    {
        return "drop index {$this->wrap($command->index)}";
    }

    /**
     * Compile a drop fulltext index command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropFullText(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileDropIndex($blueprint, $command);
    }

    /**
     * Compile a drop spatial index command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropSpatialIndex(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileDropIndex($blueprint, $command);
    }

    /**
     * Compile a drop foreign key command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileDropForeign(Blueprint $blueprint, Fluent $command)
    {
        $index = $this->wrap($command->index);

        return "alter table {$this->wrapTable($blueprint)} drop constraint {$index}";
    }

    /**
     * Compile a rename table command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileRename(Blueprint $blueprint, Fluent $command)
    {
        $from = $this->wrapTable($blueprint);

        return "alter table {$from} rename to " . $this->wrapTable($command->to);
    }

    /**
     * Compile a rename index command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileRenameIndex(Blueprint $blueprint, Fluent $command)
    {
        return sprintf(
            'alter index %s rename to %s',
            $this->wrap($command->from),
            $this->wrap($command->to)
        );
    }

    /**
     * Compile the command to enable foreign key constraints.
     *
     * @return string
     */
    public function compileEnableForeignKeyConstraints()
    {
        return 'SET CONSTRAINTS ALL IMMEDIATE;';
    }

    /**
     * Compile the command to disable foreign key constraints.
     *
     * @return string
     */
    public function compileDisableForeignKeyConstraints()
    {
        return 'SET CONSTRAINTS ALL DEFERRED;';
    }

    /**
     * Compile a comment command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileComment(Blueprint $blueprint, Fluent $command)
    {
        return sprintf(
            'comment on column %s.%s is %s',
            $this->wrapTable($blueprint),
            $this->wrap($command->column->name),
            "'" . str_replace("'", "''", $command->value) . "'"
        );
    }

    /**
     * Compile a table comment command.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $command
     * @return string
     */
    public function compileTableComment(Blueprint $blueprint, Fluent $command)
    {
        return sprintf(
            'comment on table %s is %s',
            $this->wrapTable($blueprint),
            "'" . str_replace("'", "''", $command->comment) . "'"
        );
    }

    /**
     * Quote-escape the given tables, views, or types.
     *
     * @param  array  $names
     * @return array
     */
    public function escapeNames($names)
    {
        return array_map(static function ($name) {
            return '"' . collect(explode('.', $name))
                ->map(fn ($segment) => trim($segment, '\'"'))
                ->implode('"."') . '"';
        }, $names);
    }

    /**
     * Create the column definition for a char type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeChar(Fluent $column)
    {
        if ($column->length) {
            return "char({$column->length})";
        }

        return 'char';
    }

    /**
     * Create the column definition for a string type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeString(Fluent $column)
    {
        if ($column->length) {
            return "varchar({$column->length})";
        }

        return 'varchar';
    }

    /**
     * Create the column definition for a tiny text type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeTinyText(Fluent $column)
    {
        return 'varchar(255)';
    }

    /**
     * Create the column definition for a text type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeText(Fluent $column)
    {
        return 'text';
    }

    /**
     * Create the column definition for a medium text type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeMediumText(Fluent $column)
    {
        return 'text';
    }

    /**
     * Create the column definition for a long text type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeLongText(Fluent $column)
    {
        return 'text';
    }

    /**
     * Create the column definition for an integer type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeInteger(Fluent $column)
    {
        return $this->generatableColumn('integer', $column);
    }

    /**
     * Create the column definition for a big integer type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeBigInteger(Fluent $column)
    {
        return $this->generatableColumn('bigint', $column);
    }

    /**
     * Create the column definition for a medium integer type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeMediumInteger(Fluent $column)
    {
        return $this->generatableColumn('integer', $column);
    }

    /**
     * Create the column definition for a tiny integer type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeTinyInteger(Fluent $column)
    {
        return $this->generatableColumn('smallint', $column);
    }

    /**
     * Create the column definition for a small integer type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeSmallInteger(Fluent $column)
    {
        return $this->generatableColumn('smallint', $column);
    }

    /**
     * Create the column definition for a generatable column.
     *
     * @param  string  $type
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function generatableColumn($type, Fluent $column)
    {
        if (!$column->autoIncrement && is_null($column->generatedAs)) {
            return $type;
        }

        if ($column->autoIncrement && is_null($column->generatedAs)) {
            return with([
                'integer' => 'serial',
                'bigint' => 'bigserial',
                'smallint' => 'smallserial',
            ])[$type];
        }

        $options = '';

        if (!is_bool($column->generatedAs) && !empty($column->generatedAs)) {
            $options = sprintf(' (%s)', $column->generatedAs);
        }

        return sprintf(
            '%s generated %s as identity%s',
            $type,
            $column->always ? 'always' : 'by default',
            $options
        );
    }

    /**
     * Create the column definition for a float type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeFloat(Fluent $column)
    {
        return $this->typeDouble($column);
    }

    /**
     * Create the column definition for a double type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeDouble(Fluent $column)
    {
        return 'double precision';
    }

    /**
     * Create the column definition for a real type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeReal(Fluent $column)
    {
        return 'real';
    }

    /**
     * Create the column definition for a decimal type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeDecimal(Fluent $column)
    {
        return "decimal({$column->total}, {$column->places})";
    }

    /**
     * Create the column definition for a boolean type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeBoolean(Fluent $column)
    {
        return 'boolean';
    }

    /**
     * Create the column definition for an enumeration type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeEnum(Fluent $column)
    {
        return sprintf(
            'varchar(255) check ("%s" in (%s))',
            $column->name,
            $this->quoteString($column->allowed)
        );
    }

    /**
     * Create the column definition for a json type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeJson(Fluent $column)
    {
        return 'json';
    }

    /**
     * Create the column definition for a jsonb type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeJsonb(Fluent $column)
    {
        return 'jsonb';
    }

    /**
     * Create the column definition for a date type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeDate(Fluent $column)
    {
        return 'date';
    }

    /**
     * Create the column definition for a date-time type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeDateTime(Fluent $column)
    {
        return $this->typeTimestamp($column);
    }

    /**
     * Create the column definition for a date-time (with time zone) type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeDateTimeTz(Fluent $column)
    {
        return $this->typeTimestampTz($column);
    }

    /**
     * Create the column definition for a time type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeTime(Fluent $column)
    {
        return 'time' . (is_null($column->precision) ? '' : "($column->precision)") . ' without time zone';
    }

    /**
     * Create the column definition for a time (with time zone) type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeTimeTz(Fluent $column)
    {
        return 'time' . (is_null($column->precision) ? '' : "($column->precision)") . ' with time zone';
    }

    /**
     * Create the column definition for a timestamp type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeTimestamp(Fluent $column)
    {
        $columnType = 'timestamp' . (is_null($column->precision) ? '' : "($column->precision)") . ' without time zone';

        return $column->useCurrent ? "$columnType default CURRENT_TIMESTAMP" : $columnType;
    }

    /**
     * Create the column definition for a timestamp (with time zone) type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeTimestampTz(Fluent $column)
    {
        $columnType = 'timestamp' . (is_null($column->precision) ? '' : "($column->precision)") . ' with time zone';

        return $column->useCurrent ? "$columnType default CURRENT_TIMESTAMP" : $columnType;
    }

    /**
     * Create the column definition for a year type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeYear(Fluent $column)
    {
        return $this->typeInteger($column);
    }

    /**
     * Create the column definition for a binary type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeBinary(Fluent $column)
    {
        return 'bytea';
    }

    /**
     * Create the column definition for a uuid type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeUuid(Fluent $column)
    {
        return 'uuid';
    }

    /**
     * Create the column definition for an IP address type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeIpAddress(Fluent $column)
    {
        return 'inet';
    }

    /**
     * Create the column definition for a MAC address type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeMacAddress(Fluent $column)
    {
        return 'macaddr';
    }

    /**
     * Create the column definition for a spatial Geometry type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeGeometry(Fluent $column)
    {
        return $this->formatPostGisType('geometry', $column);
    }

    /**
     * Create the column definition for a spatial Point type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typePoint(Fluent $column)
    {
        return $this->formatPostGisType('point', $column);
    }

    /**
     * Create the column definition for a spatial LineString type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeLineString(Fluent $column)
    {
        return $this->formatPostGisType('linestring', $column);
    }

    /**
     * Create the column definition for a spatial Polygon type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typePolygon(Fluent $column)
    {
        return $this->formatPostGisType('polygon', $column);
    }

    /**
     * Create the column definition for a spatial GeometryCollection type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeGeometryCollection(Fluent $column)
    {
        return $this->formatPostGisType('geometrycollection', $column);
    }

    /**
     * Create the column definition for a spatial MultiPoint type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeMultiPoint(Fluent $column)
    {
        return $this->formatPostGisType('multipoint', $column);
    }

    /**
     * Create the column definition for a spatial MultiLineString type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    public function typeMultiLineString(Fluent $column)
    {
        return $this->formatPostGisType('multilinestring', $column);
    }

    /**
     * Create the column definition for a spatial MultiPolygon type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeMultiPolygon(Fluent $column)
    {
        return $this->formatPostGisType('multipolygon', $column);
    }

    /**
     * Create the column definition for a spatial MultiPolygonZ type.
     *
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    protected function typeMultiPolygonZ(Fluent $column)
    {
        return $this->formatPostGisType('multipolygonz', $column);
    }

    /**
     * Format the column definition for a PostGIS spatial type.
     *
     * @param  string  $type
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string
     */
    private function formatPostGisType($type, Fluent $column)
    {
        if ($column->isGeometry === null) {
            return sprintf('geography(%s, %s)', $type, $column->projection ?? '4326');
        }

        if ($column->projection !== null) {
            return sprintf('geometry(%s, %s)', $type, $column->projection);
        }

        return "geometry({$type})";
    }

    /**
     * Get the SQL for a collation column modifier.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string|null
     */
    protected function modifyCollate(Blueprint $blueprint, Fluent $column)
    {
        if (!is_null($column->collation)) {
            return ' collate ' . $this->wrapValue($column->collation);
        }
    }

    /**
     * Get the SQL for a nullable column modifier.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string|null
     */
    protected function modifyNullable(Blueprint $blueprint, Fluent $column)
    {
        return $column->nullable ? ' null' : ' not null';
    }

    /**
     * Get the SQL for a default column modifier.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string|null
     */
    protected function modifyDefault(Blueprint $blueprint, Fluent $column)
    {
        if (!is_null($column->default)) {
            return ' default ' . $this->getDefaultValue($column->default);
        }
    }

    /**
     * Get the SQL for an auto-increment column modifier.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string|null
     */
    protected function modifyIncrement(Blueprint $blueprint, Fluent $column)
    {
        if ((in_array($column->type, $this->serials) || ($column->generatedAs !== null)) && $column->autoIncrement) {
            return ' primary key';
        }
    }

    /**
     * Get the SQL for a generated virtual column modifier.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string|null
     */
    protected function modifyVirtualAs(Blueprint $blueprint, Fluent $column)
    {
        if ($column->virtualAs !== null) {
            return " generated always as ({$column->virtualAs})";
        }
    }

    /**
     * Get the SQL for a generated stored column modifier.
     *
     * @param  \AwesomeCoder\Database\Schema\Blueprint  $blueprint
     * @param  \AwesomeCoder\Support\Fluent  $column
     * @return string|null
     */
    protected function modifyStoredAs(Blueprint $blueprint, Fluent $column)
    {
        if ($column->storedAs !== null) {
            return " generated always as ({$column->storedAs}) stored";
        }
    }
}
