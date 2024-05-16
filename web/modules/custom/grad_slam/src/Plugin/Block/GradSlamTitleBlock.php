<?php

namespace Drupal\grad_slam\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'GradSlamTitleBlock' block.
 *
 * @Block(
 *  id = "grad_slam_title_block",
 *  admin_label = @Translation("Grad Slam Title Block")
 * )
 */
class GradSlamTitleBlock extends BlockBase implements ContainerFactoryPluginInterface {


  protected $routeMatch;

  protected function getNode() {
    $obj = $this->routeMatch->getParameter('node');
    if (!$obj instanceof NodeInterface) {
      throw new \UnexpectedValueException("Not a node page");
    }

    return $obj;
  }

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $route_match
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $node = $this->getNode();

    if($node->bundle() != 'grad_slam'){
      switch ($node->bundle()) {
        case 'grad_slam_schedule_event':
          $grad_slam_id = \Drupal::entityQuery('node')
            ->condition('type', 'grad_slam')
            ->condition('field_grad_slam_schedule.target_id', $node->id(), '=')
            ->accessCheck(FALSE)
            ->execute();

          if($grad_slam_id) {
            $nid = reset($grad_slam_id);
            $node = \Drupal\node\Entity\Node::load($nid);
          }
          break;
      }
    }

    $node_alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/'.$node->id());

    $links = [];

    if($node->field_grad_slam_past_showcase->value != '1') {
      
      if(count($node->field_grad_slam_about->referencedEntities()) > 0) {
        $links += ["Overview" => $node_alias];
      }

      if(count($node->field_grad_slam_info->referencedEntities()) > 0) {
        $links += ["Info for Presenters" => $node_alias . "/info"];
      }

      if(count($node->field_grad_slam_workshops->referencedEntities()) > 0) {
        $links += ["Workshops" => $node_alias . "/workshops"];
      }
      
      if(count($node->field_grad_slam_schedule->referencedEntities()) > 0) {
        $links += ["Schedule" => $node_alias . "/schedule"];
      }

      if(count($node->field_prizes_and_sponsors->referencedEntities()) > 0) {
        $links += ["Prizes and Sponsors" => $node_alias . "/prizes-and-sponsors"];
      }

      if(count($node->field_grad_slam_final_round->referencedEntities()) > 0) {
        $links += ["Final Round" => $node_alias . "/final-round"];
      }

    }

    $field_background_color = $node->get('field_background_color')->color;
    $field_secondary_navigation_backg = $node->get('field_secondary_navigation_backg')->color;

    $build = [
      '#theme' => 'grad_slam_title_block',
      '#markup' => '<p class="display-large"><strong><a href="' . $node_alias .'">' . $node->getTitle() . '</a></strong></p>',
      'links' => $links,
      'field_background_color' => $field_background_color,
      'field_secondary_navigation_backg' => $field_secondary_navigation_backg,
    ];

    return $build;
  }

  public function getCacheTags() {
    $node = $this->getNode();
    return Cache::mergeTags(parent::getCacheTags(), ['node:' . $node->id()]);
  }

  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

}
