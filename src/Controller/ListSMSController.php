<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListSMSController extends AbstractController
{
    public function __invoke()
    {
        $messages = $this->getDoctrine()->getRepository(\App\Entity\SMS::class)->findAll();

        return $this->render('sms/index.html.twig', [
            'messages' => $messages,
        ]);
    }
}
