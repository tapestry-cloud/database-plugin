<?php

namespace TapestryCloud\Database;

use Doctrine\DBAL\Connection;

class Migrator
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var array
     */
    private $migrations = [
        'CREATE TABLE IF NOT EXISTS environments ('.
            'id INTEGER PRIMARY KEY,' .
            'name TEXT NOT NULL'.
        ')',

        'CREATE TABLE IF NOT EXISTS content_types ('.
            'id INTEGER PRIMARY KEY,' .
            'environment_id INTEGER NOT NULL,'.
            'name TEXT NOT NULL,'.
            'path TEXT NOT NULL,'.
            'template TEXT NOT NULL,'.
            'permalink TEXT NOT NULL,'.
            'enabled INTEGER NOT NULL DEFAULT 0'.
        ')',

        'CREATE TABLE IF NOT EXISTS files ('.
            'id INTEGER PRIMARY KEY,' .
            'environment_id INTEGER NOT NULL,'.
            'uid TEXT NOT NULL'.
        ')',

        'CREATE TABLE IF NOT EXISTS taxonomies ('.
            'id INTEGER PRIMARY KEY,' .
            'name TEXT NOT NULL'.
        ')',

        'CREATE TABLE IF NOT EXISTS content_type_taxonomies ('.
            'id INTEGER PRIMARY KEY,' .
            'content_type_id INTEGER NOT NULL,'.
            'taxonomy_id INTEGER NOT NULL,'.
            'FOREIGN KEY (content_type_id)'.
            'REFERENCES content_types(content_type_id) ON UPDATE CASCADE ON DELETE CASCADE,'.
            'FOREIGN KEY (taxonomy_id)'.
            'REFERENCES taxonomies(taxonomy_id) ON UPDATE CASCADE ON DELETE CASCADE'.
        ')',

        'CREATE TABLE IF NOT EXISTS taxonomy_classifications ('.
            'id INTEGER PRIMARY KEY,' .
            'taxonomy_id INTEGER NOT NULL,'.
            'name TEXT NOT NULL,'.
            'FOREIGN KEY (taxonomy_id)'.
            'REFERENCES taxonomies(taxonomy_id) ON UPDATE CASCADE ON DELETE CASCADE'.
        ')',

        'CREATE TABLE IF NOT EXISTS taxonomy_classification_file ('.
            'id INTEGER PRIMARY KEY,' .
            'taxonomy_classification_id INTEGER NOT NULL,'.
            'file_id INTEGER NOT NULL,'.
            'FOREIGN KEY (taxonomy_classification_id)'.
            'REFERENCES taxonomy_classifications(taxonomy_classification_id) ON UPDATE CASCADE ON DELETE CASCADE,'.
            'FOREIGN KEY (file_id)'.
            'REFERENCES files(file_id) ON UPDATE CASCADE ON DELETE CASCADE'.
        ')',
    ];

    /**
     * Exporter constructor.
     * @param Connection $connection
     * @throws \Exception
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function tables()
    {
        return count($this->migrations);
    }

    public function migrate()
    {
        foreach ($this->migrations as $migration) {
            $this->connection->exec($migration);
        }
    }
}