<?php

namespace Drupal\bits_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Egulias\EmailValidator\EmailValidator;

/**
 * Implements the SimpleForm form controller.
 *
 * This example demonstrates a simple form with a single text input element. We
 * extend FormBase which is the simplest form base class used in Drupal.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class SimpleForm extends FormBase {

  protected $currentUser;
  protected $database;
  protected $emailValidator;

  public function __construct(AccountInterface $current_user, Connection $database,
                              EmailValidator $email_validator){
      $this->currentUser = $current_user;
      $this->database = $database;
      $this->emailValidator = $email_validator;
  }

  public static function create(ContainerInterface $container){

    return new static(
      $container->get("current_user"),
      $container->get("database"),
      $container->get("email.validator")
    );

  }
  /**
   * Build the simple form.
   *
   * A build form method constructs an array that defines how markup and
   * other form elements are included in an HTML form.
   *
   * @param array $form
   *   Default form array structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('Title bits forms'),
      '#required' => TRUE,
    ];

    $form['user_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User'),
      '#description' => $this->t('Description user'),
      '#required' => TRUE,
      '#default_value' => $this->currentUser->getAccountName(),
      //'#disabled' => TRUE,
    ];

    $form['user_email'] = [
      '#type' => 'email',
      '#title' => $this->t('User email'),
      '#description' => $this->t('Your email.'),
      '#field_prefix' => '<div class="field_prefix">',
      '#field_suffix' => '</div>',
      '#prefix' => '<div class="prefix">',
      '#suffix' => '</div>',
      '#required' => TRUE,
      '#default_value' => $this->currentUser->getEmail(),

    ];


    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Getter method for Form ID.
   *
   * The form ID is used in implementations of hook_form_alter() to allow other
   * modules to alter the render array built by this form controller. It must be
   * unique site wide. It normally starts with the providing module's name.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'bits_forms_simple_form';
  }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $title = $form_state->getValue('title');
    $email = $form_state->getValue('user_email');
    $uname = $form_state->getValue('user_name');

    if (strlen($title) < 5 || strlen($title) > 30) {
      $form_state->setErrorByName('title', $this->t('The title must be between 5 and 30 characters.'));
    }

    if(!$this->emailValidator->isValid($email)) {
      $form_state->setErrorByName('user_email', $this->t('%email is
      not a valid email address.', ['%email' => $email]));
    }

    if(!empty($this->currentUser->getAccountName())){
      if($this->currentUser->getAccountName() != $uname){
        $form_state->setErrorByName('user_name', $this->t('The username does not correspond.'));
      }
    }



  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*
     * This would normally be replaced by code that actually does something
     * with the title.
     */

    $this->database->insert('bits_forms_simple')
      ->fields([
        'title' => $form_state->getValue('title'),
        'username' => empty($this->currentUser->getAccountName()) ? 0 : $form_state->getValue('user_name'),
        'email' => $form_state->getValue('user_email'),
        'uid' => $this->currentUser->id(),
        'ip' => \Drupal::request()->getClientIP(),
        'timestamp' => REQUEST_TIME,
      ])->execute();

    drupal_set_message($this->t('The form has been submited correctly'));

    \Drupal::logger('simple_forms')->notice('New Simple Form entry form user %username inserted: %title',
      [
        '%username' => $form_state->getValue('user_name'),
        '%title' => $form_state->getValue('title')
      ]);

    $form_state->setRedirect('bits_forms.simple');

  }

}
