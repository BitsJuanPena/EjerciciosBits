<?php

namespace Drupal\training_drupal_8\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller routines for page example routes.
 */
class TrainingDrupal8Controller extends ControllerBase {



  public function hello() {
    return [
      '#markup' => '<p>' . $this->t('Simple page: Training Drupal 8') . '</p>',
    ];
  }

}
