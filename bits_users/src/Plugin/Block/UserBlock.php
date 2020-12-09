<?php

namespace Drupal\bits_users\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

use Drupal\Core\Access\AccessResult;
/**
 * Provides a 'BITS: User' block.
 *
 * @Block(
 *   id = "bits_users_id",
 *   admin_label = @Translation("BITS: User")
 * )
 */
class UserBlock extends BlockBase implements ContainerFactoryPluginInterface{

  /**
   * {@inheritdoc}
   */

  protected $currentUser;

  public function __construct(array $configuration,
                                $plugin_id,
                                $plugin_definition,
                                AccountInterface $current_user) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->currentUser = $current_user;
        $this->database = $database;
  }

  public static function create(ContainerInterface $container, array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('current_user'),
        $container->get('database')
    );
  }

  public function build() {

    $list[] = $this->t('Id: ').$this->currentUser->id();
    $list[] = $this->t('Display name: ').$this->currentUser->getDisplayName();

    $roles = $this->currentUser->getRoles();
    foreach($roles as $rol){
        $list[] = $this->t('Rol: ').$rol;
    }

    $list[] = $this->t('Email: ').$this->currentUser->getEmail();
    $list[] = $this->t('last access date: ').\Drupal::service('date.formatter')->format($this->currentUser->getLastAccessedTime(),'short');

    $div = "<ul>";
    foreach($list as $item){
        $div .= "<li>".$item."</li>";
    }
    $div .= "</ul>";

    return [
      '#markup' => $div,
      '#title' => $this->t('Data User')
    ];
  }

  public function getCacheMaxAge(){
      return 0;
  }

  protected function blockAccess(AccountInterface $account){
    return AccessResult::allowedIfHasPermission($account, 'access user block');
  }

}