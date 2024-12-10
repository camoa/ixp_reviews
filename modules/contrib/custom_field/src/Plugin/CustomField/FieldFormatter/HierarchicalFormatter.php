<?php

namespace Drupal\custom_field\Plugin\CustomField\FieldFormatter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\custom_field\Plugin\CustomFieldTypeInterface;

/**
 * Plugin implementation of the 'hierarchical_term_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "hierarchical_term_formatter",
 *   label = @Translation("Hierarchical term"),
 *   description = @Translation("Display the term hierarchy."),
 *   field_types = {
 *     "entity_reference",
 *   }
 * )
 */
class HierarchicalFormatter extends EntityReferenceFormatterBase {

  /**
   * Returns a list of supported display options.
   *
   * @return array
   *   An array whose keys are display machine names
   *   and whose values are their labels.
   */
  private function displayOptions(): array {
    return [
      'all' => $this->t('The selected term and all of its parents'),
      'parents' => $this->t('Just the parent terms'),
      'root' => $this->t('Just the topmost/root term'),
      'nonroot' => $this->t('Any non-topmost/root terms'),
      'leaf' => $this->t('Just the selected term'),
    ];
  }

  /**
   * Returns a list of supported wrapping options.
   *
   * @return array
   *   An array whose keys are wrapper machine names
   *   and whose values are their labels.
   */
  private function wrapOptions(): array {
    return [
      'none' => $this->t('None'),
      'span' => $this->t('@tag elements', ['@tag' => '<span>']),
      'div' => $this->t('@tag elements', ['@tag' => '<div>']),
      'ul' => $this->t('@tag elements surrounded by a @parent_tag', [
        '@tag' => '<li>',
        '@parent_tag' => '<ul>',
      ]),
      'ol' => $this->t('@tag elements surrounded by a @parent_tag', [
        '@tag' => '<li>',
        '@parent_tag' => '<ol>',
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array {
    return [
      'hierarchy_display' => 'all',
      'hierarchy_link' => FALSE,
      'hierarchy_wrap' => 'none',
      'hierarchy_separator' => ' » ',
      'hierarchy_reverse' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state, array $settings) {
    $settings += static::defaultSettings();
    $element['hierarchy_display'] = [
      '#title' => $this->t('Terms to display'),
      '#description' => $this->t('Choose what terms to display.'),
      '#type' => 'select',
      '#options' => $this->displayOptions(),
      '#default_value' => $settings['hierarchy_display'],
    ];
    $element['hierarchy_link'] = [
      '#title' => $this->t('Link each term'),
      '#description' => $this->t('If checked, the terms will link to their corresponding term pages.'),
      '#type' => 'checkbox',
      '#default_value' => $settings['hierarchy_link'],
    ];
    $element['hierarchy_reverse'] = [
      '#title' => $this->t('Reverse order'),
      '#description' => $this->t('If checked, children display first, parents last.'),
      '#type' => 'checkbox',
      '#default_value' => $settings['hierarchy_reverse'],
    ];
    $element['hierarchy_wrap'] = [
      '#title' => $this->t('Wrap each term'),
      '#description' => $this->t('Choose what type of html elements you would like to wrap the terms in, if any.'),
      '#type' => 'select',
      '#options' => $this->wrapOptions(),
      '#default_value' => $settings['hierarchy_wrap'],
    ];
    $element['hierarchy_separator'] = [
      '#title' => $this->t('Separator'),
      '#description' => $this->t('Enter some text or markup that will separate each term in the hierarchy. Leave blank for no separator. Example: <em>»</em>'),
      '#type' => 'textfield',
      '#size' => 20,
      '#default_value' => $settings['hierarchy_separator'],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function formatValue(FieldItemInterface $item, CustomFieldTypeInterface $field, array $settings) {
    /** @var \Drupal\taxonomy\Entity\Term $entity */
    $entity = $settings['value'];

    if (!$entity instanceof EntityInterface) {
      return NULL;
    }

    /** @var \Drupal\taxonomy\TermStorageInterface $storage */
    $storage = $this->entityTypeManager->getStorage($entity->getEntityTypeId());
    $access = $this->checkAccess($entity);
    if (!$access->isAllowed()) {
      return NULL;
    }

    $formatter_settings = $settings['formatter_settings'] + static::defaultSettings();
    $tid = $entity->id();
    $term_tree = [];

    switch ($formatter_settings['hierarchy_display']) {
      case 'leaf':
        $term_tree = [$entity];
        break;

      case 'root':
        $parents = $storage->loadAllParents($tid);
        if (!empty($parents)) {
          $term_tree = [array_pop($parents)];
        }
        break;

      case 'parents':
        $term_tree = array_reverse($storage->loadAllParents($tid));
        array_pop($term_tree);
        break;

      case 'nonroot':
        $parents = $storage->loadAllParents($tid);
        if (count($parents) > 1) {
          $term_tree = array_reverse($parents);
          // This gets rid of the first topmost term.
          array_shift($term_tree);
          // Terms can have multiple parents. Now remove any remaining topmost
          // terms.
          foreach ($term_tree as $key => $term) {
            $has_parents = $storage->loadAllParents($term->id());
            // This has no parents and is topmost.
            if (empty($has_parents)) {
              unset($term_tree[$key]);
            }
          }
        }
        break;

      default:
        $term_tree = array_reverse($storage->loadAllParents($tid));
        break;
    }

    // Change output order if Reverse order is checked.
    if ($formatter_settings['hierarchy_reverse'] && count($term_tree)) {
      $term_tree = array_reverse($term_tree);
    }

    // Remove empty elements caused by discarded items.
    $term_tree = array_filter($term_tree);

    foreach ($term_tree as $index => $term) {
      if ($term->hasTranslation($item->getLangcode())) {
        $term_tree[$index] = $term->getTranslation($item->getLangcode());
      }
    }

    $build = [
      '#theme' => 'custom_field_hierarchical_formatter',
      '#terms' => $term_tree,
      '#wrapper' => $formatter_settings['hierarchy_wrap'],
      '#separator' => $formatter_settings['hierarchy_separator'],
      '#link' => $formatter_settings['hierarchy_link'],
    ];

    return $this->renderer->render($build);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity) {
    return $entity->access('view label', NULL, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(CustomFieldTypeInterface $custom_item): bool {
    return $custom_item->getTargetType() === 'taxonomy_term';
  }

}
