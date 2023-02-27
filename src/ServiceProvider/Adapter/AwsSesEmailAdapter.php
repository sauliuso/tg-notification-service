<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;

use App\ServiceProvider\AbstractEmailAdapter;
use AsyncAws\Ses\Input\SendEmailRequest;
use AsyncAws\Ses\SesClient;
use AsyncAws\Ses\ValueObject\Body;
use AsyncAws\Ses\ValueObject\Content;
use AsyncAws\Ses\ValueObject\Destination;
use AsyncAws\Ses\ValueObject\EmailContent;
use AsyncAws\Ses\ValueObject\Message;

final class AwsSesEmailAdapter extends AbstractEmailAdapter
{
    private string $accessKey;
    private string $secret;
    private string $region;

    public function __construct(string $accessKey, string $secret, string $region)
    {
        $this->accessKey = $accessKey;
        $this->secret = $secret;
        $this->region = $region;
    }

    public function getProviderName(): string
    {
        return 'awsses';
    }

    public function send(): array
    {
        $ses = new SesClient([
            'accessKeyId' => $this->accessKey,
            'accessKeySecret' => $this->secret,
            'region' => $this->region,
        ]);

        $result = $ses->sendEmail(
            new SendEmailRequest([
                'FromEmailAddress' => $this->getFromEmail(),
                'Content' => new EmailContent([
                    'Simple' => new Message([
                        'Subject' => new Content(['Data' => $this->getSubj()]),
                        'Body' => new Body([
                            'Text' => new Content(['Data' => $this->getBody()]),
                        ]),
                    ]),
                ]),
                'Destination' => new Destination([
                    'ToAddresses' => [$this->getToEmail()]
                ]),
            ]));

        return [
            'messageId' => $result->getMessageId(),
        ];
    }
}