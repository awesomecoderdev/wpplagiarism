<?php

namespace AwesomeCoder\Database\PDO;

use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use AwesomeCoder\Database\PDO\Concerns\ConnectsToDatabase;

class MySqlDriver extends AbstractMySQLDriver
{
    use ConnectsToDatabase;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pdo_mysql';
    }
}
