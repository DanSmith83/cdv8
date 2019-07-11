<?php

namespace App\MessageHandler;

use App\Message\SmsNotification;
use App\SMSService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var SMSService
     */
    private $smsService;

    public function __construct(SMSService $smsService, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->smsService = $smsService;
    }

    public function __invoke(SmsNotification $message)
    {
        $sms = $this->entityManager->getRepository(\App\Entity\SMS::class)->find($message->getSmsId());

        try {
            $this->smsService->sendMessage($sms->getRecipientNumber(), $sms->getText());
            $sms->setStatus(\App\Entity\SMS::$statuses['PENDING']);
            $this->entityManager->persist($sms);
            $this->entityManager->flush();
        } catch (\Exception $e) {

        }
    }
}