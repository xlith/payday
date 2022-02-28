<?php

namespace Drupal\payday\Services;

use Drupal\payday\Utils\Csv;

/**
 * This service provides functions that can be used in the output process.
 */
class PaydayOutput {

  /**
   * Write data to a file.
   */
  public function writeToFile(string $filename, string $data) {
    $file = fopen($filename, 'w');
    fwrite($file, $data);
    fclose($file);
  }

  /**
   * Create a CSV data from PayMonth objects using PayDay Names.
   *
   * @param string[] $payDayNames
   *   Names of the PayDay Items.
   * @param \Drupal\payday\PayMonthItem[] $payMonthItems
   *   PayMonthItems array.
   *
   * @return string
   *   Returns a string that contains CSV data.
   */
  public function generateCsvFromPayMonths(array $payDayNames, array $payMonthItems): string {
    $header = array_map(function ($v) {
      return $v . "_date";
    }, $payDayNames);
    array_unshift($header, 'month');
    $csv = new CSV($header);

    foreach ($payMonthItems as $payMonthItem) {
      $row = [];
      $row[] = $payMonthItem->monthDate->monthName;
      foreach ($payDayNames as $payDayName) {
        $row[] = $payMonthItem->getPayDateItem($payDayName)->getValidatedPayDate()->format('d-m-Y');
      }
      $csv->addRow($row);
    }

    return $csv->getData();
  }

}
