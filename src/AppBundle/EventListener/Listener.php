<?php
/**
 * Created by PhpStorm.
 * User: dasha
 * Date: 25.12.15
 * Time: 16:11
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Manager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class Listener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Manager) {
            return;
        }

        foreach($entity->getPresenters() as $presenter){
            $presenter->setIsActive(false);
        }

    }

}