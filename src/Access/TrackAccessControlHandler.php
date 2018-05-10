<?php

namespace Drupal\tracks\Access;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\tracks\Entity\TrackInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Access controller for the Track entity type.
 */
class TrackAccessControlHandler extends EntityAccessControlHandler implements EntityHandlerInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * TrackAccessControlHandler constructor.
   *
   * @param EntityTypeInterface $entity_type
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   */
  public function __construct(EntityTypeInterface $entity_type, EntityTypeManager $entityTypeManager) {
    parent::__construct($entity_type);
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      // Injecting this service just for demonstration purposes. It is not used
      // anywhere and should not be injected if not used.
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var TrackInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view track entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit track entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete track entities');
    }

    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add track entities');
  }
}
