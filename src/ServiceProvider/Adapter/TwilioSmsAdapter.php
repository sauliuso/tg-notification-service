<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;

use App\ServiceProvider\AdapterException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

final class TwilioSmsAdapter extends AbstractSmsAdapter
{
    private string $accountSid;
    private string $authToken;
    private string $phoneNumber;
    private Client $client;

    public function __construct(string $accountSid, string $authToken, string $phoneNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->phoneNumber = $phoneNumber;
    }

    public function getProviderName(): string
    {
        return 'twilio';
    }

    public function initialize(): void
    {
        $this->client = new Client($this->accountSid, $this->authToken);
    }

    public function send(): array
    {
        try {
            $message = $this->client->messages->create(
                $this->getToNumber(),
                [
                    'from' => $this->phoneNumber,
                    'body' => $this->getBody(),
                ]
            );

            return ['sid' => $message->sid];
        } catch (TwilioException $e) {
            throw new AdapterException($e->getMessage(), $e->getCode(), $e);
        }
    }
}