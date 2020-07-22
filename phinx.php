<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'test',
        'test' => [
            'adapter' => 'pgsql',
            'host' => 'localhost',
            'name' => 'async_db_test',
            'user' => 'postgres',
            'pass' => null,
            'port' => 5432,
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
