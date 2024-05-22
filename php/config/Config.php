<?php

class Config
{
    static $db = [
        'driver' => 'mysql',
        'host' => 'mariadb',
        'port' => 3306,
        'schema' => 'bici',
        'username' => 'root',
        'password' => 'root'
    ];

    static $cors_domain = 'http://localhost:3000';
}
