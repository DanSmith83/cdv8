<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SMSRepository")
 */
class SMS
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\Column(type="string", length=140)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sent_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=11)
     * @Assert\Regex(
     *     pattern="/^(07)(?:\d\s?){9}$/",
     *     match=true,
     *     message="Please supply an eleven digit UK mobile number starting with '07'"
     * )
     */
    private $recipient_number;

    public static $statuses = [
      'UNSENT'  => 1,
      'PENDING' => 2,
      'FAILED'  => 3,
      'SENT'    => 4,
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sent_at;
    }

    public function setSentAt(?\DateTimeInterface $sent_at): self
    {
        $this->sent_at = $sent_at;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getStatusText(): ?string
    {
        $statuses = array_flip(self::$statuses);
        $status = $this->getStatus();

        if ($status && array_key_exists($status, $statuses)) {
            return $statuses[$status];
        }

        return null;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRecipientNumber(): ?string
    {
        return $this->recipient_number;
    }

    public function setRecipientNumber(string $recipient_number): self
    {
        $this->recipient_number = $recipient_number;

        return $this;
    }
}
