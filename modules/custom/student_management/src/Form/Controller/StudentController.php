<?php

namespace Drupal\student_management\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class StudentController extends ControllerBase {

  public function list() {
    $header = ['ID', 'Name', 'Age', 'Course'];
    $rows = [];

    $conn = Database::getConnection();
    $results = $conn->select('student_data', 's')
      ->fields('s', ['id', 'name', 'age', 'course'])
      ->execute();

    foreach ($results as $record) {
      $rows[] = [$record->id, $record->name, $record->age, $record->course];
    }

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No students found.'),
    ];
  }
}
