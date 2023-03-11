<?php

namespace AwesomeCoder\Database\PDO;

use Doctrine\DBAL\Driver\AbstractSQLiteDriver;
use AwesomeCoder\Database\PDO\Concerns\ConnectsToDatabase;

class SQLiteDriver extends AbstractSQLiteDriver
{
    use ConnectsToDatabase;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pdo_sqlite';
    }
}
