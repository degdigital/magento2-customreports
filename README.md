# DEG Custom Reports

This module was inspired by the Magento 1 extension for custom reports (https://github.com/kalenjordan/custom-reports).
Thank you to Kalen Jordan and all who contributed to that project.

## Description

Easily create reports with custom SQL queries and display them using the Magento admin grid.

## Disclaimer

This module has the potential to make irreversible changes to your database if set up incorrectly. Use at your own risk.

## Features

* Report result table
* Usage of a read-only database connection
* Column filtering
* Column sorting
* CSV export
* Automated exports

## Adding a read-only database connection

A read-only database connection is a requirement for this module.

```php
#!php

    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'host_name',
                'dbname' => 'database_name',
                'username' => 'database_user',
                'password' => 'database_password',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'driver_options' => [
                    1014 => false,
                ],
            ],
            'readonly' => [
                'host' => 'host_name',
                'dbname' => 'database_name',
                'username' => 'database_readonly_user',
                'password' => 'database_readonly_password',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'driver_options' => [
                    1014 => false,
                ],
            ],
        ],
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default',
        ],
        'readonly' => [
            'connection' => 'readonly',
        ],
    ],
```
