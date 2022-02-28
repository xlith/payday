<?php

namespace Drupal\payday;

use Carbon\Carbon;

/**
 * An object to store {@link \Drupal\payday\PayDayItem}'s.
 */
class PayMonthItem {

  /**
   * Date that PayMonth belongs to.
   *
   * This will most likely store the month date as a base date, and
   * it will be used to create {@link \Drupal\payday\PayDayItem}'s.
   *
   * @var \Carbon\Carbon
   */
  public $monthDate;

  /**
   * Array to store {@link \Drupal\payday\PayDayItem}'s.
   *
   * @var PayDayItem[]
   */
  public $payDayItems;

  /**
   * Creates a new PayMonthItem.
   *
   * @param \Carbon\Carbon $monthDate
   *   See {@link $monthDate}.
   * @param array $payDays
   *   See {@link $payDays}.
   */
  public function __construct(Carbon $monthDate, array $payDays = []) {

    $this->monthDate = $monthDate;
    $this->payDayItems = $payDays;

  }

  /**
   * Creates a new {@link \Drupal\payday\PayDayItem}.
   *
   * Newly created object will be attached to the {@link $payDays} array.
   *
   * @param string $name
   *   See {@link \Drupal\payday\PayDayItem::$name}.
   * @param \Carbon\Carbon $date
   *   See {@link \Drupal\payday\PayDayItem::$date}.
   * @param \Closure $calculation
   *   See {@link \Drupal\payday\PayDayItem::$calculation}.
   * @param array $validations
   *   See {@link \Drupal\payday\PayDayItem::$validations}.
   */
  public function createPayDay(string $name, Carbon $date, \Closure $calculation, array $validations) {
    $this->payDayItems[] = new PayDayItem($name, $date, $calculation, $validations);
  }

  /**
   * Returns the {@link \Drupal\payday\PayDayItem} that has the name.
   *
   * @TODO This method should be engineered. Right now it only returns
   * the first item that has the requested name.
   * Either make the name unique or return an array.
   *
   * @return \Drupal\payday\PayDayItem
   *   Pay day item.
   */
  public function getPayDateItem(string $name): ?PayDayItem {
    foreach ($this->payDayItems as $payDayItem) {
      if ($payDayItem->getName() == $name) {
        return $payDayItem;
      }
    }
    return NULL;
  }

}
