# PAYDAY

This is a small [Drupal](https://www.drupal.org) module to help a fictional company determine the dates they need to pay salaries to their sales department.

This module integrates with [Drush](https://www.drush.org/) and creates a new drush command that can be invoked through terminal.

* This module heavily depends on [Carbon](https://carbon.nesbot.com/) library.
* For business logic see service class at \Drupal\payday\Services\Payday
* And finally code is documented with comments and DocBlocks. If you are confused at any stage feel free to ask.

## REQUIREMENTS

* Drupal 9
* drush
* php 7.3

## INSTALL

```shell
$ composer require xlith/payday
```

## USAGE

### SCHEDULE

```
Generate payday schedule until the end of this year.

Examples:
  drush payday:schedule schedule.csv This will create the csv data and write it in a file named schedule.csv.
  drush payday:schedule              This will create a csv data and output to the stdout.

Arguments:
  [filename] Filename for CSV file. (Write including the file extension.) If this parameter is omitted output will be stdout.

Aliases: pds
```

### INFO

```
Outputs info about the payday module.

Examples:
  drush payday:info Outputs info about the payday module.

Options:
  --format=FORMAT Format the result data. Available formats: csv,json,list,null,php,print-r,sections,string,table,tsv,var_dump,var_export,xml,yaml [default: yaml]
  --fields=FIELDS Limit output to only the listed elements. Name top-level elements by key, e.g. "--fields=name,date", or use dot notation to select a nested element, e.g. "--fields=a.b.c as example".
  --field=FIELD   Select just one field, and force format to *string*.

Aliases: pdi
```

## TESTS

In order to run the unit-tests module must be installed in a Drupal instance and this particular instance must be set accordingly.
Please refer to the Drupal Unit Tests guide for more detailed info.

```shell
phpunit --configuration phpunit.xml modules/payday/tests
```
