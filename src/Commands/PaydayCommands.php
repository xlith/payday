<?php

namespace Drupal\payday\Commands;

use Carbon\Carbon;
use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\payday\Services\PaydayOutput;
use Drupal\payday\Services\Payday;
use Drush\Commands\DrushCommands;

/**
 * Drush command file that provides drush terminal commands for payday module.
 */
class PaydayCommands extends DrushCommands {

  /**
   * The payday.payday service.
   *
   * @var \Drupal\payday\Services\Payday
   */
  protected $payday;

  /**
   * The payday.output service.
   *
   * @var \Drupal\payday\Services\PaydayOutput
   */
  protected $paydayOutput;

  /**
   * Constructs a PaydayCommands object.
   *
   * @param \Drupal\payday\Services\Payday $payday
   *   The payday.payday service.
   * @param \Drupal\payday\Services\PaydayOutput $paydayOutput
   *   The payday.output service.
   */
  public function __construct(Payday $payday, PaydayOutput $paydayOutput) {
    parent::__construct();
    $this->payday = $payday;
    $this->paydayOutput = $paydayOutput;
  }

  /**
   * Generate payday schedule until the end of this year.
   *
   * @param string|null $filename
   *   Filename for CSV file. (Write including the file extension.)
   *   If this parameter is omitted output will be stdout.
   *
   * @return string|null
   *   Returns a csv string or null
   *
   * @usage drush payday:schedule schedule.csv
   *   This will create the csv data and write it in a file named schedule.csv.
   *
   * @usage drush payday:schedule
   *   This will create a csv data and output to the stdout.
   *
   * @command payday:schedule
   * @aliases pds
   */
  public function schedule(string $filename = NULL): ?string {

    $payDaySchedule = $this->payday->buildPayDaySchedule(Carbon::today());
    $csvData = $this->paydayOutput->generateCsvFromPayMonths(["Salary", "Bonus"], $payDaySchedule);
    if ($filename) {
      $filename = $this->getConfig()->cwd() . '/' . $filename;
      $this->paydayOutput->writeToFile($filename, $csvData);
      $this->logger()->success(dt('Created file: ') . $filename);
      return NULL;
    }
    else {
      return $csvData;
    }
  }

  /**
   * Outputs info about the payday module.
   *
   * @usage drush payday:info
   *   Outputs info about the payday module.
   *
   * @command payday:info
   * @aliases pdi
   */
  public function info(): RowsOfFields {
    $content = file_get_contents(dirname(__FILE__) . '/../../composer.json');
    $content = json_decode($content, TRUE);
    $rows = [
      ['Package Name', $content['name']],
      ['Description', $content['description']],
      ['Package License', $content['license']],
      ['Author Name', $content['authors'][0]['name']],
      ['Author Email', $content['authors'][0]['email']],
    ];

    return new RowsOfFields($rows);
  }

}
