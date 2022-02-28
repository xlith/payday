<?php

namespace Drupal\payday\Services;

use Carbon\Carbon;
use Drupal\payday\PayDayValidation;
use Drupal\payday\PayMonthItem;

/**
 * Main service class for payday module.
 */
class Payday {

  /**
   * Returns an array that contains {@link \Drupal\payday\PayMonthItem}'s.
   *
   * @param \Carbon\Carbon $startDay
   *   The date to start the calculations.
   *
   * @return array
   *   Array of {@link \Drupal\payday\PayMonthItem}'s
   */
  public function buildPayDaySchedule(Carbon $startDay): array {

    // Closure for salary pay date calculation.
    $calculateSalaryPayDate = function (Carbon $date) {
      return $date->lastOfMonth();
    };

    // Closures for salary pay date validation.
    $salaryPayDayValidation = new PayDayValidation(
      function (Carbon $date) {
        if ($date->isWeekend()) {
          return FALSE;
        }
        return TRUE;
      },
      function (Carbon $date) {
        return $date->previousWeekday();
      });

    // Closure for bonus pay date calculation.
    $calculateBonusPayDate = function (Carbon $date) {
      return Carbon::createFromDate($date->year, $date->month, 15);
    };

    // Closures for bonus pay date validation.
    $bonusPayDayValidation = new PayDayValidation(
      function (Carbon $date) {
        if ($date->isWeekend()) {
          return FALSE;
        }
        return TRUE;
      },
      function (Carbon $date) {
        return $date->nextWeekday()->addDays(2);
      });

    $payMonthArray = [];

    // Array loop to create the schedule array that contains
    // all the validations and the results together.
    for ($payMonthNumber = $startDay->month; $payMonthNumber <= 12; $payMonthNumber++) {
      $payMonthDate = Carbon::create($startDay->year, $payMonthNumber);
      $payMonth = new PayMonthItem($payMonthDate);
      $payMonth->createPayDay("salary", $payMonth->monthDate, $calculateSalaryPayDate, [$salaryPayDayValidation]);
      $payMonth->createPayDay("bonus", $payMonth->monthDate, $calculateBonusPayDate, [$bonusPayDayValidation]);
      $payMonthArray[] = $payMonth;
    }

    return $payMonthArray;

  }

}
