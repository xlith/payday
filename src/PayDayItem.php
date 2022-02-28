<?php

namespace Drupal\payday;

use Carbon\Carbon;

/**
 * An object to store data for the payment date.
 *
 * Foundation stone of the application. This class holds the main data for
 * the calculations. However, it doesn't have any business logic inside.
 * Calculations and validations are passed as a Closure.
 * It's advised to read all the doc blocks in this
 * class beginning with {@link \Drupal\payday\PayDayItem::validatePayDate}
 *
 * @see \Drupal\payday\PayDayItem::validatePayDate()
 * @see \Drupal\payday\PayDayItem::$calculation
 * @see \Drupal\payday\PayDayItem::$validations
 */
class PayDayItem {

  /**
   * Date that PayDay belongs to.
   *
   * This will most likely store the month date as a base date, and
   * it will be used to calculate {@link $payDate}
   * and {@link $calculatedPayDate}.
   *
   * @var \Carbon\Carbon
   */
  protected $date;

  /**
   * Initial calculated date.
   *
   * This value is calculated by {@link $calculation} closure.
   *
   * @var \Carbon\Carbon|null
   */
  private $payDate;

  /**
   * Validated date.
   *
   * This value is the calculated after taking {@link $payDate} as argument
   * and running all the {@see $validations} and fixes.
   *
   * @var \Carbon\Carbon|null
   */
  private $validatedPayDate;

  /**
   * Validation class array.
   *
   * @var \Drupal\payday\PayDayValidation[]
   *
   * @see \Drupal\payday\PayDayValidation
   */
  protected $validations;

  /**
   * Name of the pay date. i.e. salary, bonus etc.
   *
   * @var string
   */
  private $name;

  /**
   * Function that calculates intended payment date.
   *
   * This function takes @link $date as an argument
   * and return @link $payDate as a @link \Carbon\Carbon object.
   *
   * @var \Closure
   *
   * @code $calculateBonusPayDate = function (Carbon $date) {
   * return Carbon::createFromDate($date->year, $date->month, 15);
   * };
   * @endcode
   */
  private $calculation;

  /**
   * Create a new PayDayItem.
   *
   * @param string $name
   *   See {@link $name}.
   * @param \Carbon\Carbon $date
   *   See {@link $date}.
   * @param \Closure $calculation
   *   See {@link $calculation}.
   * @param PayDayValidation[] $validations
   *   See {@link $validations}.
   */
  public function __construct(string $name, Carbon $date, \Closure $calculation, array $validations) {

    $this->date = $date;

    $this->payDate = NULL;

    $this->calculation = $calculation;
    $this->payDate = $this->calculation->call($this, $this->date);

    $this->validations = $validations;
    $this->validatedPayDate = $this->payDate;
    $this->validatedPayDate = $this->validatePayDate();
    $this->name = $name;
  }

  /**
   * Runs the validations.
   *
   * Runs every validation in {@link $validations} array
   * if any of them fails runs the closure in the same array.
   * This method passes {@link $validatedPayDate} to every validation and fix
   * method and expects validation to return {@link bool} and
   * fix to return {@link Carbon} as a new {@link $validatedPayDate}
   * that will be seeded to the next validation function.
   *
   * @return \Carbon\Carbon
   *   Returns a new {@link Carbon object as the new {@link $validatedPayDate}
   */
  public function validatePayDate(): Carbon {
    foreach ($this->validations as $validation) {
      if (!$validation->validation->call($this, $this->validatedPayDate)) {
        $this->validatedPayDate = $validation->fix->call($this, $this->validatedPayDate);
      }
    }

    return $this->payDate;
  }

  /**
   * Adds a new validation to the {@link $validations} array.
   *
   * @param PayDayValidation $payDayValidation
   *   New validation.
   */
  public function addValidation(PayDayValidation $payDayValidation) {
    $this->validations[] = $payDayValidation;
  }

  /**
   * Sets a new closure as {@link $calculation} method.
   *
   * @param \Closure $calculation
   *   Closure to set as a new {@link $calculation} method.
   */
  public function setCalculation(\Closure $calculation): void {
    $this->calculation = $calculation;
  }

  /**
   * Returns the {@link $validatedPayDate}.
   *
   * @return \Carbon\Carbon
   *   Validated pay date.
   */
  public function getValidatedPayDate(): Carbon {
    return $this->validatedPayDate;
  }

  /**
   * Returns the name of the pay date.
   *
   * @return string
   *   Pay day name.
   */
  public function getName(): string {
    return $this->name;
  }

}
