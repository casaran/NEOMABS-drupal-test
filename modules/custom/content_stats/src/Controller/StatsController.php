<?php

declare(strict_types=1);

namespace Drupal\content_stats\Controller;

use Drupal\content_stats\StatsService;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Build the content stats page.
 */
class StatsController extends ControllerBase {

  /**
   * Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The stats service.
   *
   * @var \Drupal\content_stats\StatsService
   */
  protected $statsService;

  /**
   * Creates the controller.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\content_stats\StatsService $stats_service
   *    The stats service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    StatsService $stats_service) {
    $this->entityTypeManager = $entity_type_manager;
    $this->statsService = $stats_service;
  }

  /**
   * Create function.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Dependency Injection Container.
   *
   * @return \Drupal\dic_core\Controller\SyncAccountController
   *   The Controller interface.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('content_stats.service'),
    );
  }

  /**
   * Returns stats content page.
   *
   * @return array
   *   A renderable array.
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function build() {
    // Get all content types.
    $storage = $this->entityTypeManager->getStorage('node_type');
    $node_types_array = $storage->loadMultiple();

    $rows = [];

    $headers = [t('Type'), t('Total'), t('Publié'), t('Non publié')];
    foreach($node_types_array as $node_type => $node_types_data) {
      $total = $this->statsService->getTotalNumberContent($node_type);
      $total_published = $this->statsService->getNumberStatusContent($node_type, 1);
      $total_unpublished = $this->statsService->getNumberStatusContent($node_type, 0);

      $rows[$node_type]['data'][0]['data']['#plain_text'] = $node_types_data->label();
      $rows[$node_type]['data'][1]['data']['#plain_text'] = $total;
      $rows[$node_type]['data'][2]['data']['#plain_text'] = $total_published;
      $rows[$node_type]['data'][3]['data']['#plain_text'] = $total_unpublished;
    }
    return [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
    ];
  }

}
