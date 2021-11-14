<?php

namespace StudentBundle\EventListener;

use StudentBundle\Service\ClearCacheService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;

class ClearCacheSubscriber implements EventSubscriberInterface
{
    private ClearCacheService $clearCacheService;

    public function __construct(ClearCacheService $clearCacheService)
    {
        $this->clearCacheService = $clearCacheService;
    }

    public function getSubscribedEvents(): array
    {
        return array(
            Events::onFlush,
        );
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity)
            $this->clearCacheService->clearCache($entity);

        foreach ($uow->getScheduledEntityUpdates() as $entity)
            $this->clearCacheService->clearCache($entity);

        foreach ($uow->getScheduledEntityDeletions() as $entity)
            $this->clearCacheService->clearCache($entity);
    }
}