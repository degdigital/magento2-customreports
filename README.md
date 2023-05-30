# DEG Custom Reports

This module was inspired by the Magento 1 extension for custom reports (https://github.com/kalenjordan/custom-reports).
Thank you to Kalen Jordan and all who contributed to that project.

## Description
Easily create reports with custom SQL queries and display them using the Magento admin grid.

## Installation

```
composer config repositories.degdigital-magento2-customreport vcs https://github.com/degdigital/magento2-customreports.git
composer require degdigital/magento2-customreports
bin/magento setup:upgrade
```

## Creating a readonly connection

It is advised to use a readonly connection in order to avoid accidentally updating/inserting/deleting data. 

First, add a readonly user to your MySQL instance;

```
CREATE USER 'readonly'@'localhost' IDENTIFIED BY 'readonly-password';
GRANT SELECT on your-database.* to 'readonly'@'localhost';
```

Then, add a connection called `readonly` in `app/etc/env.php`:

```php
<?php
return [
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                ... your default connection
            ],
            'readonly' => [
                'host' => 'localhost',
                'dbname' => 'your-database',
                'username' => 'readonly',
                'password' => 'readonly-password',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'driver_options' => [
                    1014 => false
                ]
            ]
        ]
    ]
];
```

The extension will now automatically use the `readonly` connection.

## Usage
In the admin, navigate to Reports > (Custom Reports) Custom Reports. Click Add New to add a new report.

## Disclaimer
This module has the potential to make irreversible changes to your database if set up incorrectly.  Use at your own risk.

## Features
* Report result table
* Usage of a read-only database connection
* Column filtering
* Column sorting
* CSV export
* Automated exports

