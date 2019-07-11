<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Messenger\MessageBusInterface;

class SendSMSController extends AbstractController implements RateLimitedController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request)
    {
        $sms = new \App\Entity\SMS;
        $sms->setCreatedAt(new \DateTime('now'));
        $sms->setSender($this->getUser());
        $sms->setStatus(1);

        $form = $this->createFormBuilder($sms)
            ->add('text', TextareaType::class)
            ->add('recipientNumber', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sms = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sms);
            $entityManager->flush();

            return $this->redirectToRoute('sms_list');
        }

        return $this->render('sms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
