<?php

namespace Drupal\payday\Utils;

/**
 * Utility class to create a CSV data.
 */
class Csv {
  /**
   * Data string that holds the csv.
   *
   * @var string
   */
  protected $data;

  /**
   * Returns the CSV data as string.
   *
   * @return string
   *   returns the data string.
   */
  public function getData(): string {
    return $this->data;
  }

  /**
   * Constructor of the CSV class. Creates first row with the headers.
   *
   * @params array $columns
   * @returns void
   */
  public function __construct($columns) {
    $this->data = '"' . implode('","', $columns) . '"' . "\n";
  }

  /**
   * Adds a new row to the CSV data.
   *
   * @params array $row
   * @returns void
   */
  public function addRow($row) {
    $this->data .= '"' . implode('","', $row) . '"' . "\n";
  }

}
