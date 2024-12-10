<?php

namespace Drupal\custom_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Entity\TranslatableInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The base formatter for custom_field.
 */
abstract class BaseFormatter extends FormatterBase implements BaseFormatterInterface {

  /**
   * The custom field type manager.
   *
   * @var \Drupal\custom_field\Plugin\CustomFieldTypeManagerInterface
   */
  protected $customFieldManager;

  /**
   * The custom field formatter manager.
   *
   * @var \Drupal\custom_field\Plugin\CustomFieldFormatterManagerInterface
   */
  protected $customFieldFormatterManager;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity repository.
   *
   * @var \Drupal\Core\Entity\EntityRepositoryInterface
   */
  protected $entityRepository;

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->customFieldManager = $container->get('plugin.manager.custom_field_type');
    $instance->customFieldFormatterManager = $container->get('plugin.manager.custom_field_formatter');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->entityRepository = $container->get('entity.repository');
    $instance->moduleHandler = $container->get('module_handler');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array {
    return [
      'fields' => [],
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    $form_id = $form_state->getFormObject()->getFormId();
    $field_settings = $this->getFieldSettings();
    $field_name = $this->fieldDefinition->getName();
    $is_views_form = $this->moduleHandler->moduleExists('views_ui') && $form_id == 'views_ui_config_item_form';

    $form['fields'] = [
      '#type' => 'details',
      '#title' => $this->t('Field settings'),
      '#open' => TRUE,
    ];

    foreach ($this->getCustomFieldItems() as $name => $custom_item) {
      $settings = $this->getSetting('fields')[$name] ?? [];
      $formatter_settings = $settings['formatter_settings'] ?? [];
      $type = $custom_item->getPluginId();
      $formatter_options = $this->customFieldFormatterManager->getOptions($custom_item);
      $default_format = $custom_item->getDefaultFormatter();
      if (isset($settings['format_type']) && isset($formatter_options[$settings['format_type']])) {
        $default_format = $settings['format_type'];
      }
      $trigger = $form_state->getTriggeringElement();
      $trigger_match = 'fields[' . $field_name . '][settings_edit_form][settings][fields][' . $name . '][format_type]';
      // Views config form has different field keys.
      if ($is_views_form) {
        $trigger_match = 'options[settings][fields][' . $name . '][format_type]';
      }

      if (!empty($trigger) && $trigger['#name'] == $trigger_match) {
        $format_type = $trigger['#value'];
      }
      else {
        $format_type = $default_format;
      }

      $form['#visibility_path'] = $this->customFieldFormatterManager->getInputPathForStatesApi($form_state, $field_name, $name);
      $form['#storage_settings'] = $field_settings['columns'][$name];
      $form['#field_settings'] = $field_settings['field_settings'][$name] ?? [];
      $wrapper_id = 'field-' . $field_name . '-' . $name . '-ajax';
      $form['fields'][$name] = [
        '#type' => 'details',
        '#title' => $this->t('@label (@type)', [
          '@label' => $custom_item->getLabel(),
          '@type' => $custom_item->getDataType(),
        ]),
      ];
      if (!empty($formatter_options)) {
        $form['fields'][$name]['format_type'] = [
          '#type' => 'select',
          '#title' => $this->t('Format type'),
          '#options' => $formatter_options,
          '#default_value' => $format_type,
          '#ajax' => [
            'callback' => [$this, 'actionCallback'],
            'wrapper' => $wrapper_id,
            'method' => 'replace',
          ],
        ];
        $form['fields'][$name]['formatter_settings'] = [
          '#type' => 'container',
          '#prefix' => '<div id="' . $wrapper_id . '">',
          '#suffix' => '</div>',
        ];
        $formatter = [];
        // Get the formatter settings form.
        /** @var \Drupal\custom_field\Plugin\CustomFieldFormatterInterface $format */
        if ($format = $this->customFieldFormatterManager->createInstance($format_type)) {
          $formatter = $format->settingsForm($form, $form_state, $formatter_settings);
        }
        $form['fields'][$name]['formatter_settings'] += $formatter;
      }
      // Add label_display field to everything but checkboxes.
      if ($type !== 'boolean') {
        $form['fields'][$name]['formatter_settings']['label_display'] = [
          '#type' => 'select',
          '#title' => $this->t('Label display'),
          '#options' => $this->fieldLabelOptions(),
          '#default_value' => $formatter_settings['label_display'] ?? 'above',
          '#weight' => 10,
        ];
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(): array {
    $summary = parent::settingsSummary();
    $custom_fields = $this->getCustomFieldItems();
    $settings = $this->getSetting('fields');
    foreach ($custom_fields as $id => $custom_field) {
      $formatter_options = $this->customFieldFormatterManager->getOptions($custom_field);
      $format_type = $custom_field->getDefaultFormatter();
      if (isset($settings[$id]['format_type']) && isset($formatter_options[$settings[$id]['format_type']])) {
        $format_type = $settings[$id]['format_type'];
      }
      $definition = $this->customFieldFormatterManager->getDefinition($format_type);
      $field_label = $custom_field->getLabel();
      $format_label = $definition['label'];
      $formatted_summary = new FormattableMarkup(
        '<strong>@label</strong>: @format_label', [
          '@label' => $field_label,
          '@format_label' => $format_label,
        ]
      );
      $summary[] = $this->t('@summary', ['@summary' => $formatted_summary]);
    }

    return $summary;
  }

  /**
   * Ajax callback for changing format type.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function actionCallback(array $form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $wrapper_id = $trigger['#ajax']['wrapper'];

    // Get the current parent array for this widget.
    $parents = $trigger['#array_parents'];
    $sliced_parents = array_slice($parents, 0, -1, TRUE);

    // Get the updated element from the form structure.
    $updated_element = NestedArray::getValue($form, $sliced_parents)['formatter_settings'];

    // Create an AjaxResponse.
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#' . $wrapper_id, $updated_element));

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item, $langcode);
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareView(array $entities_items) {
    $ids = [];
    $custom_items = $this->getCustomFieldItems();
    foreach ($entities_items as $items) {
      foreach ($items as $item) {
        foreach ($custom_items as $custom_item) {
          $target_type = $custom_item->getTargetType();
          $value = $item->{$custom_item->getName()};
          if (!empty($target_type) && !empty($value)) {
            $ids[$target_type][] = $value;
          }
        }
      }
    }
    if ($ids) {
      foreach ($ids as $target_type => $entity_ids) {
        $target_entities[$target_type] = $this->entityTypeManager->getStorage($target_type)->loadMultiple($entity_ids);
      }
    }
    foreach ($entities_items as $items) {
      foreach ($items as $item) {
        foreach ($custom_items as $custom_item) {
          $target_type = $custom_item->getTargetType();
          $value = $item->{$custom_item->getName()};
          if (!empty($target_type) && !empty($value)) {
            if (isset($target_entities[$target_type][$value])) {
              $item->{$custom_item->getName()} = ['entity' => $target_entities[$target_type][$value]];
            }
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function viewValue(FieldItemInterface $item, string $langcode): array {
    return [];
  }

  /**
   * Get the custom field items for this field.
   *
   * @return \Drupal\custom_field\Plugin\CustomFieldTypeInterface[]
   *   An array of custom field items.
   */
  public function getCustomFieldItems(): array {
    return $this->customFieldManager->getCustomFieldItems($this->fieldDefinition->getSettings());
  }

  /**
   * Returns an array of formatted custom field item values for a singe field.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   The field item.
   * @param string $langcode
   *   The language code.
   *
   * @return array
   *   An array of formatted values.
   */
  protected function getFormattedValues(FieldItemInterface $item, string $langcode) {
    $settings = $this->getSetting('fields');
    $values = [];
    $properties = $item->getProperties();
    foreach ($this->getCustomFieldItems() as $name => $custom_item) {
      $value = $custom_item->value($item);
      if ($value === '' || $value === NULL) {
        continue;
      }
      if (method_exists($properties[$name], 'getEntity')) {
        $entity = $properties[$name]->getEntity();

        // Set the entity in the correct language for display.
        if ($entity instanceof TranslatableInterface) {
          $entity = $this->entityRepository->getTranslationFromContext($entity, $langcode);
        }
        $value = $entity;
      }
      $format_settings = $settings[$name] ?? [
        'formatter_settings' => [],
      ];
      $format_settings += [
        'configuration' => $custom_item->configuration,
        'widget_settings' => $custom_item->getWidgetSetting('settings'),
        'value' => $value,
        'langcode' => $item->getLangcode(),
      ];
      $format_type = $custom_item->getDefaultFormatter();
      // Get the available formatter options for this field type.
      $formatter_options = $this->customFieldFormatterManager->getOptions($custom_item);
      if (isset($format_settings['format_type']) && isset($formatter_options[$format_settings['format_type']])) {
        $format_type = $format_settings['format_type'];
      }

      $plugin = $this->customFieldFormatterManager->createInstance($format_type);
      if (method_exists($plugin, 'formatValue')) {
        $value = $plugin->formatValue($item, $custom_item, $format_settings);
        if ($value === '' || $value === NULL) {
          continue;
        }
      }
      if (method_exists($plugin, 'defaultSettings')) {
        $format_settings += $plugin->defaultSettings();
      }
      $markup = [
        'name' => $name,
        'value' => [
          '#markup' => $value,
        ],
        'label' => $custom_item->getLabel(),
        'label_display' => $format_settings['formatter_settings']['label_display'] ?? 'above',
        'type' => $custom_item->getPluginId(),
      ];
      $values[$name] = $markup;
    }

    return $values;
  }

  /**
   * Returns an array of visibility options for custom field labels.
   *
   * Copied from Drupal\field_ui\Form\EntityViewDisplayEditForm (can't call
   * directly since it's protected)
   *
   * @return array
   *   An array of visibility options.
   */
  protected function fieldLabelOptions(): array {
    return [
      'above' => $this->t('Above'),
      'inline' => $this->t('Inline'),
      'hidden' => '- ' . $this->t('Hidden') . ' -',
      'visually_hidden' => '- ' . $this->t('Visually hidden') . ' -',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    $fields = $this->getSetting('fields');
    $dependencies = parent::calculateDependencies();
    if (!empty($fields)) {
      foreach ($fields as $field) {
        if (!isset($field['formatter_settings'])) {
          continue;
        }
        $plugin = $this->customFieldFormatterManager->createInstance($field['format_type']);
        if (method_exists($plugin, 'calculateFormatterDependencies')) {
          $plugin_dependencies = $plugin->calculateFormatterDependencies($field['formatter_settings']);
          $dependencies = array_merge_recursive($dependencies, $plugin_dependencies);
        }
      }
    }

    return $dependencies;
  }

  /**
   * {@inheritdoc}
   */
  public function onDependencyRemoval(array $dependencies) {
    $changed = parent::onDependencyRemoval($dependencies);
    $settings_changed = FALSE;
    $fields = $this->getSetting('fields');
    foreach ($fields as $name => $field) {
      if (!isset($field['formatter_settings'])) {
        continue;
      }
      $plugin = $this->customFieldFormatterManager->createInstance($field['format_type']);
      if (method_exists($plugin, 'onFormatterDependencyRemoval')) {
        $changed_settings = $plugin->onFormatterDependencyRemoval($dependencies, $field['formatter_settings']);
        if (!empty($changed_settings)) {
          $fields[$name]['formatter_settings'] = $changed_settings;
          $settings_changed = TRUE;
        }
      }
    }

    if ($settings_changed) {
      $this->setSetting('fields', $fields);
    }

    $changed |= $settings_changed;

    return $changed;
  }

}
