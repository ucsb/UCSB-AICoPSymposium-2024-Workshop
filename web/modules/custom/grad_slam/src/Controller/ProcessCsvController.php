<?php

namespace Drupal\grad_slam\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Config\FileStorage;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\Core\Entity\EntityInterface;
use Drupal\feeds\Feeds\Item\ItemInterface;

/**
 * Controller for the Clas Schedules
 */
class ProcessCsvController extends ControllerBase {
    public function importPresenters(EntityInterface $presenter, ItemInterface $feeds_item){
        try {

            $department = $feeds_item->get('department'); 
            $year = $feeds_item->get('year'); 

            // Get the department taxonomy term id
            $properties = [];
            $properties = [
                'name' => $department,
                'vid' => 'grad_slam_department'
            ];
            $department_id = 0;
            $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties($properties);
            if(count($terms) >= 1) {
                foreach($terms as $term) {
                    $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($term->id());
                    if(!empty($parent)) {
                        $department_id = $term->id();
                    }
                }
            }

            $presenter->field_attendee_department = [ 'target_id' => $department_id ];
            $presenter->save();


            // Get the Grad Slam Year taxonomy 
            $tids = \Drupal::entityQuery('taxonomy_term')
                ->condition('vid', "grad_slam_year")
                ->condition('name', $year)
                ->accessCheck(FALSE)
                ->execute();
            $year_id = reset($tids);

            // Get the Grad Slam entity
            $grad_slam_ids = \Drupal::entityQuery('node')
                ->condition('type', 'grad_slam')
                ->condition('field_grad_slam_year.target_id', $year_id)
                ->accessCheck(FALSE)
                ->execute();
            $grad_slam_id = reset($grad_slam_ids);
            $grad_slam = \Drupal\Node\Entity\Node::load($grad_slam_id);


            $grad_slam->field_grad_slam_attendee[] = ['target_id' => $presenter->id()];
            $grad_slam->save(); // Save the GradSlam so you don't need to refresh the cache


        } catch (\Exception $e) {
            
            \Drupal::messenger()->addError('The import process experienced an error.', $e->getMessage());
            \Drupal::logger('grad_slam')->error($e->getMessage());
        }

    }


}