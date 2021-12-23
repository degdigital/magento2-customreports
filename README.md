# DEG Custom Reports

This module was inspired by the Magento 1 extension for custom reports (https://github.com/kalenjordan/custom-reports).  Thank you to Kalen Jordan and all who had contributed to that project.

## Description
Easily create reports with custom SQL queries and display them using the magento admin grid.

## Disclaimer
This module has the potential to make irreversable changes to your database if set up incorrectly.  Use at your own risk.

## Features
* Report result table
* Usage of a read only database connection
* Column Filtering
* Column Sorting
* CSV Export

## Adding a readonly user
```php
#!php

'db' => 
        array (
            'table_prefix' => '',
            'connection' => 
                array (
                    'default' => 
                        array (
                            'host' => '127.0.0.1',
                            'dbname' => 'database_name',
                            'username' => 'database_user',
                            'password' => 'database_password',
                            'model' => 'mysql4',
                            'engine' => 'innodb',
                            'initStatements' => 'SET NAMES utf8;',
                            'active' => '1',
                        ),
                    'readonly' =>
                        array (
                            'host' => '127.0.0.1',
                            'dbname' => 'database_name',
                            'username' => 'database_readonly_user',
                            'password' => 'database_readonly_password',
                            'model' => 'mysql4',
                            'engine' => 'innodb',
                            'initStatements' => 'SET NAMES utf8;',
                            'active' => '1',
                        ),
                ),
        ),
    'resource' =>
        array (
            'default_setup' => array ('connection' => 'default'),
            'readonly' => array('connection' => 'readonly')
        ),
```
