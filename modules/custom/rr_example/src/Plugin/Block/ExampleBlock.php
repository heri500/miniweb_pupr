<?php
namespace Drupal\rr_example\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;

/**
 * Provides a 'ExampleBlock' block.
 *
 * @Block(
 *  id = "example_block",
 *  admin_label = @Translation("Example block"),
 *  category = @Translation("Custom")
 * )
 */
class ExampleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ExampleBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_def
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */

  // create dependency injection instead of direct call to \Drupal::entityTypeManager()
  public function __construct(
    array $configuration,
          $plugin_id,
          $plugin_def,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_def);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_def) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_def,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    //I'm prefer to use templates for output rendering
    $build = [
      '#theme' => 'rr_example_article_list',
      '#items' => [],
      '#count' => 0,
    ];
    // make sure the field Show in list is exist in the article content type, if error then show the error
    try {
      $node_storage = $this->entityTypeManager->getStorage('node');
      $articles = $node_storage->loadByProperties(['type' => 'article', 'status' => 1]);
      $items = [];
      $cache_tags = [];

      foreach ($articles as $article) {
        // Add each article's cache tag
        $cache_tags = Cache::mergeTags($cache_tags, $article->getCacheTags());

        // Get field value properly, if there is no value then return false
        $show_in_list = $article->get('field_show_in_list')->value ?? FALSE;
        // Get only include articles marked to show in list
        if (!empty($show_in_list)) {
          // we must use drupal url generator rather then directly use <a> tag
          $url = Url::fromRoute('entity.node.canonical', ['node' => $article->id()]);
          // store the node title and url in items
          $items[] = [
            'title' => $article->label(),
            'url' => $url,
          ];
        }
      }
      // store the variable for use in the templates
      $build['#items'] = $items;
      $build['#count'] = count($items);
      $build['#cache']['tags'] = $cache_tags;

    }
    catch (\Exception $e) {
      dpm($e->getMessage());
      // If there is an error then empty array will be returned
      return [
        '#markup' => $this->t('Error accure, unable to fetch article list.'),
      ];
    }

    return $build;
  }
}
