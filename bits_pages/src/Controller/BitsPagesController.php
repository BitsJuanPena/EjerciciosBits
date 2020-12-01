<?php

namespace Drupal\bits_pages\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Link;
use Drupal\Core\Url;

# Enrutamiento y menus
# Enrutamiento y menús - Ejercicio 1
class BitsPagesController extends ControllerBase {

  public function simple() {
    return [
      '#markup' => '<p>' . $this->t('Página con mensaje simple') . '</p>',
    ];
  }



# Enrutamiento y menus
# Enrutamiento y menús - Ejercicio 2

  public function calculator($p1, $p2) {
    
    //verificar si el datos es numerico 
    if (!is_numeric($p1) || !is_numeric($p2)) {
      throw new AccessDeniedHttpException();
    }

    $list[] = $this->t("First number was @number.", ['@number' => $p1]);
    $list[] = $this->t("Second number was @number.", ['@number' => $p2]);
    $list[] = $this->t("The total was @number.", ['@number' => $p1 + $p2]);
    $list[] = $this->t("The subtraction is @number.", ['@number' => $p1 - $p2]);
    $list[] = $this->t("The division is @number.", ['@number' => $p1 / $p2]);
    $list[] = $this->t("The multiplication is @number.", ['@number' => $p1 * $p2]);
    $list[] = $this->t("The division module is @number.", ['@number' => $p1 % $p2]);


    return [      // The theme function to apply to the #items.
      '#theme' => 'item_list',
      '#items' => $list,
      '#title' => $this->t('Calculator'),
    ];
    
  }

# Enrutamiento y menus
# Enrutamiento y menús - Ejercicio 3

public function links() {
    
  
  $link[] = Link::fromTextAndUrl("Administración de bloques");
  $link[] = Link::fromTextAndUrl("Administración de contenidos");
  $link[] = Link::fromTextAndUrl("Administración de usuarios");
  $link[] = Link::fromTextAndUrl("Enlace a la portada del sitio");
  $link[] = Link::fromTextAndUrl("Enlace al nodo con id 1");
  $link[] = Link::fromTextAndUrl("Enlace a la edición del nodo con id 1");
  $link[] = Link::fromTextAndUrl("Enlace externo a www.google.com (se debe abrir en ventana nueva).");
  
  
  return [      // The theme function to apply to the #items.
      '#theme' => 'item_list',
      '#items' => $link,
      '#title' => $this->t('Examples of links:'),
  ];
  
}


}