<?php

namespace Drupal\student_management\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class StudentForm extends FormBase {

  public function getFormId() {
    return 'student_management_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Name'),
      '#required' => TRUE,
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
      '#min' => 1,
      '#required' => TRUE,
    ];
    $form['course'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Course'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    $conn->insert('student_data')->fields([
      'name' => $form_state->getValue('name'),
      'age' => $form_state->getValue('age'),
      'course' => $form_state->getValue('course'),
      'created' => \Drupal::time()->getCurrentTime(),
    ])->execute();

    $this->messenger()->addStatus($this->t('Student saved successfully.'));
  }
}
