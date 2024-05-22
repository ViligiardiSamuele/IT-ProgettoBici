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

    static $CORS_domains = [
        'http://localhost:3000',
        'http://127.0.0.1:3000'
    ];
}
