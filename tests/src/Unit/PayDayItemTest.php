<?php

namespace Drupal\Tests\payday\Unit;

use Carbon\Carbon;
use Drupal\payday\PayDayItem;
use Drupal\payday\PayDayValidation;
use Drupal\Tests\UnitTestCase;

/**
 * Test PayDayItem class.
 *
 * @group payday
 */
class PayDayItemTest extends UnitTestCase {

  /**
   * Tests testSimpleClass.
   */
  public function testSimpleClass() {

    $payDayItem = new PayDayItem(
      "name",
      Carbon::create(2022, 1),
      function (Carbon $date) {
        return $date->firstOfMonth();
      },
      [new PayDayValidation(
        function (Carbon $date) {
          return TRUE;
        },
        function (Carbon $date) {
          return $date;
        }
        ),
      ]
    );

    self::assertTrue($payDayItem->getValidatedPayDate()->day == 1, "This needs to be first day of the month");
  }

}
