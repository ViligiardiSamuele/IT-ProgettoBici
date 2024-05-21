<?php

class Database extends PDO
{
    private static $instance;

    public function __construct($driver, $host, $port, $schema, $username, $password)
    {
        parent::__construct(
            '' . $driver .
            ':host=' . $host .
            ';port=' . $port .
            ';dbname=' . $schema,
            $username,
            $password
        );
    }

    public static function getInstance()
    {
        if (!isset($instance))
            $instance = new Database(
                Config::$db['driver'],
                Config::$db['host'],
                Config::$db['port'],
                Config::$db['schema'],
                Config::$db['username'],
                Config::$db['password']
            );
        return $instance;
    }
}
