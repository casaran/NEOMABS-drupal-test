<?php

declare(strict_types=1);

namespace Drupal\content_stats;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class for stats about content.
 */
class StatsService {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ReadingListService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *     The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Returns the total number of content for a specific content type.
   * 
   * @param string $content_type
   *     The content type.
   * 
   * @return int
   *   The total number of content for a specific content type.
   */
  public function getTotalNumberContent(string $content_type): int {
    $storage = $this->entityTypeManager->getStorage('node');
    $result = $storage->getQuery()
      ->condition('type', $content_type)
      ->accessCheck(TRUE)
      ->count()
      ->execute();

    return $result;
  }

  /**
   * Returns the total number of content for a specific content type of a specific status.
   * 
   * @param string $content_type
   *     The content type.
   * @param int $status
   *     The node status.
   * 
   * @return int
   *   The total number of content for a specific content type of a specific status.
   */
  public function getNumberStatusContent(string $content_type, int $status): int {
    $storage = $this->entityTypeManager->getStorage('node');
    $result = $storage->getQuery()
      ->condition('type', $content_type)
      ->condition('status', $status)
      ->accessCheck(TRUE)
      ->count()
      ->execute();

    return $result;
  }

}
