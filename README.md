# DEG Custom Reports

This module was inspired by the Magento 1 extension for custom reports (https://github.com/kalenjordan/custom-reports).
Thank you to Kalen Jordan and all who contributed to that project.

## Description

Easily create reports with custom SQL queries and display them using the Magento admin grid.

## Disclaimer
This module has the potential to make irreversible changes to your database if set up incorrectly.  Use at your own risk.

## Features
* Report result table
* Usage of a read-only database connection
* Column filtering
* Column sorting
* CSV, TSV, and TXT (pipe-delimited) export
* Automated exports

## Custom Reports

From the admin panel > Reports > Custom Reports > Custom Reports interface, reports can be created with arbitrary queries. 

## Automated Exports

From the admin panel > Reports > Custom Reports > Automated Exports interface, jobs can be created to export one or more custom reports. Currently, two types of exports are supported:
1. Local file drops, in which a file is created on the server in which the Magento cron runs from in a configurable location, with var/export being the suggested location.
2. Remote file drops, in which a file is created the same as a local file drop and then uploaded to an SFTP server with the provided credentials. 

If both types are selected, the system will run the query and generate the local file only once. This is useful for especially heavy queries.

A cron job will be created with the name "automated_export_<automated_export_id_here>". The configuration for the cron job resides in the core_config_data table with a path like 'crontab/default/jobs/automated_export_<automated_export_id_here>'. E.g.:

crontab/default/jobs/automated_export_1/schedule/cron_expr = 0 0 0 0 0
crontab/default/jobs/automated_export_1/run/model = DEG\CustomReports\Model\AutomatedExport\Cron::execute
crontab/default/jobs/automated_export_1/name = automated_export_1

The popular third-party Magento tool, n98-magerun, can be used to run the automated exports manually from the command line using the above name, e.g. 'n98-magerun sys:cron:run automated_export_1'. 


