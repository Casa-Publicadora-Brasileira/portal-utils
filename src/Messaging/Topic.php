<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Messaging;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\Sns\SnsClient;
use Exception;
use Illuminate\Support\Facades\Log;

class TopicDispatch implements Message
{
    private ?SnsClient $client;

    public function __construct(?string $config)
    {
        $this->client = self::client($config);
    }

    public function send(?string $arn, array $message): ?Result
    {
        if (!empty($arn)) {
            $result = $this->client?->publish([
                'TargetArn' => $arn,
                'Message' => json_encode(['default' => json_encode($message)]),
                'MessageStructure' => 'json',
                'MessageAttributes' => [
                    'Content-Type' => [
                        'DataType' => 'String',
                        'StringValue' => 'application/json',
                    ],
                ],
            ]);

            return $result;
        }

        return null;
    }

    public function fifo(?string $arn, array $message): ?Result
    {
        if (!empty($arn)) {
            $result = $this->client?->publish([
                'TargetArn' => $arn,
                'Message' => json_encode(['default' => json_encode($message)]),
                'MessageGroupId' => 1,
                'MessageDeduplicationId' => md5(uniqid(rand() . time())),
                'MessageStructure' => 'json',
                'MessageAttributes' => [
                    'Content-Type' => [
                        'DataType' => 'String',
                        'StringValue' => 'application/json',
                    ],
                ],
            ]);

            return $result;
        }

        return null;
    }

    private static function client(?string $config): ?SnsClient
    {

        $providers = [];
        $config = $config ?: 'sns';

        if ((bool) config("services.{$config}.key") && (bool) config("services.{$config}.secret")) {
            $credentials = new Credentials(config("services.{$config}.key"), config("services.{$config}.secret"), config("services.{$config}.token"));
            $providers[] = CredentialProvider::fromCredentials($credentials);
        }

        $providers[] = CredentialProvider::instanceProfile();
        $providers[] = CredentialProvider::env();
        $providers[] = CredentialProvider::ini();

        $provider = CredentialProvider::chain(...$providers);

        try {
            $provider()->wait();
        } catch (Exception $e) {
            Log::error('Não foi possível obter credenciais da AWS.', ['service' => 'SNS', 'message' => $e->getMessage(), 'exception' => $e]);

            return null;
        }

        return new SnsClient([
            'version' => 'latest',
            'region' => config("services.{$config}.region"),
            'credentials' => $provider,
        ]);
    }
}

class Topic
{
    public const ENGINE = TopicDispatch::class;

    use MessageTrait;
}
