<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ABORTED = 'aborted';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 20)]
    private ?string $channel = null;

    #[ORM\Column(type: "json")]
    private ?array $payload = null;

    #[ORM\Column(length: 255)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statusMessage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $provider = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $providerResponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusMessage(): ?string
    {
        return $this->statusMessage;
    }

    public function setStatusMessage(?string $statusMessage): self
    {
        $this->statusMessage = $statusMessage;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getProviderResponse(): ?array
    {
        return $this->providerResponse;
    }

    public function setProviderResponse(?array $providerResponse): self
    {
        $this->providerResponse = $providerResponse;

        return $this;
    }

    public function abort(string $message): self
    {
        return $this
            ->setStatus(self::STATUS_ABORTED)
            ->setStatusMessage($message);
    }

    public function isPending(): bool
    {
        return $this->getStatus() === Notification::STATUS_PENDING;
    }

    public function isFailed(): bool
    {
        return $this->getStatus() === Notification::STATUS_FAILED;
    }
}
