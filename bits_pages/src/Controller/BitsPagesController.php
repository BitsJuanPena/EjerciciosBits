<?php
/*
* @file
* Drupal\bits_pages\Controller;
*/
namespace Drupal\bits_pages\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Datetime\DateFormatter;

# Enrutamiento y menus
# Enrutamiento y menús - Ejercicio 1
class BitsPagesController extends ControllerBase {

  protected $currentUser;
  protected $dateFormatter;

  //,DateFormatter $date_formatter
  public function __construct(AccountInterface $current_user){
      $this->currentUser = $current_user;
      //$this->dateFormatter = $date_formatter;
  }

  public static function create(ContainerInterface $container){

    return new static(
      $container->get("current_user"),
      //$container->get("date_formatter")
    );

  }

  public function simple() {

    $output = '<p>' . $this->t('Page: Bits page Simple') . '</p>';

    return array(
      '#markup' => $output,
    );
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

    $output = '<p>' . $this->t('This is the content') . '</p>';
    if($this->currentUser->hasPermission('administer nodes')){
      $output .= '<p>' . $this->t('This only displayed if the
      current user can administer nodes.') .'</p>';
    }

    $output .= "<ul>";
    foreach($list as $item){
      $output .= "<li>".$item."</li>";
    }
    $output .= "</ul>";


    return array(      // The theme function to apply to the #items.
      '#markup' => $output,
      '#title' => $this->t('Calculator'),
    );

  }

# Enrutamiento y menus
# Enrutamiento y menús - Ejercicio 3
public function links() {

  $url = Url::fromUri('http://localhost/drupal8/');
  $options = ['absolute' => TRUE];
  $url1 = Url::fromRoute('entity.node.canonical', ['node' => 1], $options);
  $url2 = Url::fromRoute('entity.node.edit_form', ['node' => 1], $options);
  $urle = Url::fromUri('https://www.google.com/');

  $link[] = \Drupal::l(t("Administración de bloques"), $url);
  $link[] = \Drupal::l(t("Administración de contenidos"), $url);
  $link[] = \Drupal::l(t("Administración de usuarios"), $url);
  $link[] = \Drupal::l(t("Enlace a la portada del sitio"), $url);
  $link[] = \Drupal::l(t("Enlace al nodo con id 1"), $url1);
  $link[] = \Drupal::l(t("Enlace a la edición del nodo con id 1"), $url2);
  $link[] = \Drupal::l(t("Enlace externo a www.google.com (se debe abrir en ventana nueva)."), $urle);
  $link[] = $this->t('last access date: ').\Drupal::service('date.formatter')->format($this->currentUser->getLastAccessedTime(),'custom', 'Y:m:d');

  $div = "<ul>";
  foreach($link as $item){
    $div .= "<li>".$item."</li>";
  }
  $div .= "</ul>";

  return [
      '#markup' => $div
  ];


}


}