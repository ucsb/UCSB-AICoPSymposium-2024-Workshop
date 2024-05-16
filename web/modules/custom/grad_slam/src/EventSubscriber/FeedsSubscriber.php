<?php

namespace Drupal\grad_slam\EventSubscriber;

use Drupal\feeds\Event\EntityEvent;
use Drupal\feeds\Event\FeedsEvents;
use Drupal\feeds\Event\ImportFinishedEvent;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\grad_slam\Controller\ProcessCsvController;

/**
 * (describe what your event listener does here)
 */
class FeedsSubscriber implements EventSubscriberInterface {

    /**
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $currentUser;

    /**
     * FeedsSubscriber constructor.
     * 
     * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
     */
    public function __construct(AccountProxyInterface $currentUser){
        $this->currentUser = $currentUser;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        $events = [];
        // $events[FeedsEvents::PROCESS_ENTITY_PREVALIDATE][] = ['preValidate',0];
        $events[FeedsEvents::PROCESS_ENTITY_PRESAVE][] = ['preSave',0];
        // $events[FeedsEvents::IMPORT_FINISHED][] = ['afterImport',0];
        return $events;
    }
    
    /**
     * Called when an import has finished.
     *
     * @param \Drupal\feeds\Event\EntityEvent $event
     *   The import finished event.
     */
    public function preSave(EntityEvent $event){
        $feed_type_id = $event->getFeed()->getType()->id();

        $roles = $this->currentUser->getRoles();
        if(in_array('authenticated',$roles)) {
            
            if ($feed_type_id == 'presenter') {
                
                $utility = new ProcessCsvController();
                
                $presenter_entity = $event->getEntity();
                $import_item = $event->getItem();

                $utility-> importPresenters($presenter_entity, $import_item);
            }
        }

    }

}