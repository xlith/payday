<?php

namespace Drupal\payday;

/**
 * Class to store Validation and fixes of the Payday Items.
 */
class PayDayValidation {

  /**
   * Closure method that will be checked.
   *
   * If this function returns false then the @link $fix method will be called.
   *
   * @var \Closure
   *
   * @returns bool
   */
  public $validation;

  /**
   * Closure method that will be called if @link $validation is false.
   *
   * @var \Closure
   *
   * @returns \Carbon\Carbon
   */
  public $fix;

  /**
   * Constructor of the class.
   *
   * @param \Closure $validation
   *   Validation closure.
   * @param \Closure $fix
   *   Fix closure.
   */
  public function __construct(\Closure $validation, \Closure $fix) {
    $this->validation = $validation;
    $this->fix = $fix;
  }

}
