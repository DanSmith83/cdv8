<?php

namespace App\Message;

class SmsNotification
{
    /**
     * @var int
     */
    private $smsId;

    public function __construct(int $smsId)
    {
        $this->smsId = $smsId;
    }

    public function getSmsId(): string
    {
        return $this->smsId;
    }
}