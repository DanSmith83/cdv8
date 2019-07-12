<?php

namespace App\Controller;

use App\RateLimiter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListSMSController extends AbstractController
{
    /**
     * @var RateLimiter
     */
    private $rateLimiter;

    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    public function __invoke()
    {
        $messages = $this->getDoctrine()->getRepository(\App\Entity\SMS::class)->findAll();
        $isLimited = $this->rateLimiter->isLimited('sms-limited', $this->getUser());

        return $this->render('sms/index.html.twig', [
            'messages' => $messages,
            'isLimited'   => $isLimited,
        ]);
    }
}
