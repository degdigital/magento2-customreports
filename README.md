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
* CSV, TSV, TXT (pipe-delimited), and Excel XML exports
* Automated exports

## Custom Reports

From the admin panel > Reports > Custom Reports > Custom Reports interface, reports can be created with arbitrary queries.

### Performance

Report queries can be very slow, even enough to trigger an unavoidable gateway timeout (depending on architecture) or browser timeout and thus be unusable. This is exacerbated by (often unnecessary) queries to retrieve columns and queries to retrieve the total record count.

The query to retrieve columns now reuses the same query used to get the full result set (when filters and sorting are not applied). When filters/sorting are applied, the columns query must use its own query for the reasons outlined in `\DEG\CustomReports\Block\Adminhtml\Report\Grid::_prepareLayout`.

A flag on the custom reports called 'Allow Count Query' can be set to 'No', which will prevent the typical 'select count(*) from (original query)' query used to determine the total number of records and the total number of pages, because this data may be unnecessary on report grids for the following reasons:
* some reports may always return results far below the page size.
* some reports might only render useful data on the first page, so the total record count and the total page count are unnecessary.
* some report grids might only be used to gain access to the Export button, so all information on the grid is unnecessary.

In such cases, if the query is slow, this flag can be set to 'No' to improve the performance of the report query.

With no filters applied and 'Allow Count Query' set to 'No', the report query will only run once.

## Automated Exports

From the admin panel > Reports > Custom Reports > Automated Exports interface, jobs can be created to export one or more custom reports. Currently, two types of exports are supported:
1. Local file drops, in which a file is created on the server in which the Magento cron runs from in a configurable location, with var/export being the suggested location.
2. Remote file drops, in which a file is created the same as a local file drop and then uploaded to an (S)FTP server with the provided credentials.
3. Email, in which a file is sent as an attachment to the email recipient(s). If "Send as one combined email" is set to 'No' (the default), N*M emails will be sent with one attachment each, where N is the number of custom reports and M is the number of file types. If it is set to 'Yes', then only one email will be sent with N*M attachments.

If both types are selected, the system will run the query and generate the local file only once. This is useful for especially heavy queries.

A cron job will be created with the name "automated_export_<automated_export_id_here>". The configuration for the cron job resides in the core_config_data table with a path like `crontab/default/jobs/automated_export_<automated_export_id_here>`. E.g.:

```
crontab/default/jobs/automated_export_1/schedule/cron_expr = 0 0 0 0 0
crontab/default/jobs/automated_export_1/run/model = DEG\CustomReports\Model\AutomatedExport\Cron::execute
crontab/default/jobs/automated_export_1/name = automated_export_1
```

The popular third-party Magento tool, n98-magerun, can be used to run the automated exports manually from the command line using the above name, e.g. `n98-magerun sys:cron:run automated_export_1`.
