<?php

namespace AwesomeCoder\Database\Concerns;

use AwesomeCoder\Support\Collection;

trait ExplainsQueries
{
    /**
     * Explains the query.
     *
     * @return \AwesomeCoder\Support\Collection
     */
    public function explain()
    {
        $sql = $this->toSql();

        $bindings = $this->getBindings();

        $explanation = $this->getConnection()->select('EXPLAIN ' . $sql, $bindings);

        return new Collection($explanation);
    }
}
