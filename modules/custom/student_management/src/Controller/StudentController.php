<?php

namespace Drupal\student_management\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StudentController extends ControllerBase {

  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  public function studentList() {
    $query = $this->database->select('student_data', 's')
      ->fields('s', ['id', 'name', 'age', 'course']);
    $results = $query->execute()->fetchAllAssoc('id');

    $students = [];
    foreach ($results as $student) {
      $students[] = [
        'id' => $student->id,
        'name' => $student->name,
        'age' => $student->age,
        'course' => $student->course,
      ];
    }

    return [
      '#theme' => 'student_list',
      '#students' => $students,
      '#cache' => ['max-age' => 0],
    ];
  }
}
