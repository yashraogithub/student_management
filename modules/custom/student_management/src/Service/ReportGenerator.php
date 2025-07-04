<?php

namespace Drupal\student_management\Service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;

/**
 * Service to generate a daily student report.
 */
class ReportGenerator {

  protected $database;
  protected $logger;
  protected $dateFormatter;

  /**
   * Constructor.
   */
  public function __construct(Connection $database, LoggerChannelFactoryInterface $logger_factory, DateFormatterInterface $date_formatter) {
    $this->database = $database;
    $this->logger = $logger_factory->get('student_management');
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Run the report logic.
   */
  public function generateDailyStudentReport() {
    // Get the start of the current day (midnight).
    $start_of_day = strtotime('today');

    // Query student entries added today.
    $count = $this->database->select('student_data', 's')
      ->condition('created', $start_of_day, '>=')
      ->countQuery()
      ->execute()
      ->fetchField();

    // Format date for log message.
    $formatted_date = $this->dateFormatter->format($start_of_day, 'custom', 'Y-m-d');

    // Log the daily report.
    $this->logger->info('Daily report for @date: @count student(s) added.', [
      '@date' => $formatted_date,
      '@count' => $count,
    ]);
  }

}
