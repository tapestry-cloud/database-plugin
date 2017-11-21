<?php

namespace TapestryCloud\Database;

use PDO;
use Tapestry\Entities\Project;
use Doctrine\DBAL\Connection;

class Exporter {

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Exporter constructor.
     * @param Connection $connection
     * @throws \Exception
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        if (!$this->connection->connect()) {
            throw new \Exception('Unable to connect to database');
        }

        $tables = $this->connection->getSchemaManager()->listTables();

        if (count($tables) < 1) {
            $migrator = new Migrator($this->connection);
            $migrator->migrate();
        }

        $n = 1;
    }

    public function export(Project $project) {
        $n = 1;
    }
}