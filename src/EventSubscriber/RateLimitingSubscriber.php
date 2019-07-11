<?php

// src/EventSubscriber/TokenSubscriber.php
namespace App\EventSubscriber;

use App\Controller\RateLimitedController;
use App\RateLimiter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class RateLimitingSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var RateLimiter
     */
    private $rateLimiter;

    public function __construct(RateLimiter $rateLimiter, Security $security)
    {
        $this->rateLimiter = $rateLimiter;
        $this->security = $security;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $thing = $controller[0];
        } elseif (is_object($controller)) {
            $thing = $controller;
        } else {
            return;
        }

        if ($thing instanceof RateLimitedController) {
            if ($this->rateLimiter->isLimited('sms-limited', $this->security->getUser())) {
                $redirectUrl = '/sms';
                $event->setController(function() use ($redirectUrl) {
                    return new RedirectResponse($redirectUrl);
                });
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
