<?php

namespace App\EventListener;

use App\Message\SmsNotification;
use App\RateLimiter;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class SmsEventListener {

    /**
     * @var RateLimiter
     */
    private $rateLimiter;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(RateLimiter $rateLimiter, MessageBusInterface $messageBus)
    {
        $this->rateLimiter = $rateLimiter;
        $this->messageBus = $messageBus;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof \App\Entity\SMS) {
            return;
        }

        $this->rateLimiter->set('sms-limited', $entity->getSender());
        $this->messageBus->dispatch(new SmsNotification($entity->getId()), [
            new DelayStamp(10000)
        ]);
    }
}