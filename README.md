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

