<?php

namespace Drupal\group\Plugin\Group\RelationHandler;

/**
 * Trait for group relation post install handlers.
 */
trait PostInstallTrait {

  use RelationHandlerTrait;

  /**
   * The parent post install handler in the decorator chain.
   *
   * @var \Drupal\group\Plugin\Group\RelationHandler\PostInstallInterface|null
   */
  protected $parent = NULL;

  /**
   * {@inheritdoc}
   */
  public function getInstallTasks() {
    if (!isset($this->parent)) {
      throw new \LogicException('Using PostInstallTrait without assigning a parent or overwriting the methods.');
    }
    return $this->parent->getInstallTasks();
  }

}
