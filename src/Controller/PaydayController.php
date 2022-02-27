<?php

namespace Drupal\payday\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Payday routes.
 */
class PaydayController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
