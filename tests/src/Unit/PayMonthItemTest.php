<?php

namespace Drupal\Tests\payday\Unit;

use Carbon\Carbon;
use Drupal\payday\PayDayItem;
use Drupal\payday\PayDayValidation;
use Drupal\payday\PayMonthItem;
use Drupal\Tests\UnitTestCase;

/**
 * Test description.
 *
 * @group payday
 */
class PayMonthItemTest extends UnitTestCase {

  /**
   * Tests something.
   */
  public function testSimpleClass() {

    $payMonthItem = new PayMonthItem(
      Carbon::create(2022, 1),
      [
        new PayDayItem(
          "name1",
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
        ),
        new PayDayItem(
          "name2",
          Carbon::create(2022, 1),
          function (Carbon $date) {
            return $date->firstOfMonth()->addDays(1);
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
        ),
      ]
    );

    self::assertTrue($payMonthItem->getPayDateItem("name1")->getValidatedPayDate()->day == 1, "This needs to be first day of the month");
    self::assertTrue($payMonthItem->getPayDateItem("name2")->getValidatedPayDate()->day == 2, "This needs to be second day of the month");
  }

}
