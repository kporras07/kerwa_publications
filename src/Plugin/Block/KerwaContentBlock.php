<?php

namespace Drupal\kerwa_publications\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Provides a kerwa content block.
 *
 * @Block(
 *   id = "kerwa_publications_kerwa_content",
 *   admin_label = @Translation("Kerwa Content"),
 *   category = @Translation("Kerwa")
 * )
 */
class KerwaContentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The cache.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructs a new KerwaContentBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache.
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CacheBackendInterface $cache, EntityTypeManager $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->cache = $cache;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('cache.default'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'kerwa_option' => '',
      'items_per_page' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $items = $this->entityTypeManager->getStorage('kerwa_option')->loadMultiple();
    $options = [];
    foreach ($items as $item) {
      $options[$item->id()] = $item->label();
    }
    $form['kerwa_option'] = [
      '#type' => 'select',
      '#title' => $this->t('Kerwa Key'),
      '#default_value' => $this->configuration['kerwa_option'],
      '#options' => $options,
      '#required' => TRUE,
    ];
    $form['items_per_page'] = [
      '#type' => 'number',
      '#title' => $this->t('Items per page'),
      '#default_value' => $this->configuration['items_per_page'],
      '#min' => 0,
      '#required' => TRUE
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['kerwa_option'] = $form_state->getValue('kerwa_option');
    $this->configuration['items_per_page'] = $form_state->getValue('items_per_page');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $option = $this->entityTypeManager->getStorage('kerwa_option')->load($this->configuration['kerwa_option']);
    $build['content'] = [
      '#markup' => $this->t('OPTION: @option. Name: @name. ITEMS: @items', [
        '@option' => $option->id(),
        '@name' => $option->label(),
        '@items' => $this->configuration['items_per_page'],
      ]),
    ];
    return $build;
  }

}