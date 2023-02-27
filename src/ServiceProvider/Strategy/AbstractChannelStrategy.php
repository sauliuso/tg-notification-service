<?php

namespace App\ServiceProvider\Strategy;

use App\Dto\SendNotificationRequest;
use App\Entity\Notification;
use App\Model\User;
use App\ServiceProvider\AdapterException;
use App\ServiceProvider\AdapterInterface;
use App\ServiceProvider\ChannelStrategyInterface;
use Webmozart\Assert\Assert;

abstract class AbstractChannelStrategy implements ChannelStrategyInterface
{
    /** @var AdapterInterface[] */
    protected array $adapters = [];
    protected User $user;
    protected SendNotificationRequest $requestDto;
    protected Notification $notification;

    abstract protected function initializeAdapter(AdapterInterface $adapter): void;

    abstract public function getChannel(): string;

    public function addAdapter(AdapterInterface $adapter): void
    {
        $this->adapters[] = $adapter;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setRequestDto(SendNotificationRequest $dto): void
    {
        $this->requestDto = $dto;
    }

    public function setNotification(Notification $notification): void
    {
        $this->notification = $notification;
    }

    public function send(): array
    {
        Assert::notEmpty($this->adapters, sprintf('No adapters available for channel "%s"', $this->getChannel()));

        foreach ($this->adapters as $adapter) {
            $this->initializeAdapter($adapter);
            $this->notification->setProvider($adapter->getProviderName());
            try {
                $result = $adapter->send();
                $this->notification->setStatus(Notification::STATUS_SENT);
                $this->notification->setStatusMessage(null);
                $this->notification->setProviderResponse($result);

                return $result;
            } catch (AdapterException $e) {
                $this->notification->setStatus(Notification::STATUS_FAILED);
                $this->notification->setStatusMessage($e->getMessage());
            }
        }

        throw $e;
    }
}