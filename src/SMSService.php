<?php

namespace App;

use Twilio\Rest\Client;

class SMSService {

    /**
     * @var Client
     */
    private $client;

    private $fromNumber;

    public function __construct($accountSid, $authToken, $fromNumber) {
        $this->client = new Client($accountSid, $authToken);
        $this->fromNumber = $fromNumber;
    }

    public function sendMessage($recipientNumber, $message) {
        $recipientNumber = '+44'.substr($recipientNumber, 1);
        $this->client->messages->create(
            $recipientNumber,
            array(
                'from' => $this->fromNumber,
                'body' => $message
            )
        );
    }
}