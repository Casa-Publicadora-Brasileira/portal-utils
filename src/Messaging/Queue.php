<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Messaging;

use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\Sqs\SqsClient;
use Illuminate\Support\Str;

class QueueDispatch implements Message
{
    private ?SqsClient $client;

    public function __construct(string $config)
    {
        $this->client = self::client($config);
    }

    public function send(?string $queue, array $message): ?Result
    {
        if (!empty($queue)) {
            $result = $this->client?->sendMessage([
                'QueueUrl' => $this->client->getQueueUrl(['QueueName' => $queue])->get('QueueUrl'),
                'MessageBody' => json_encode($message),
            ]);

            return $result;
        }

        return null;
    }

    public function fifo(?string $queue, array $message): ?Result
    {
        if (!empty($queue)) {
            $result = $this->client?->sendMessage([
                'QueueUrl' => $this->client->getQueueUrl(['QueueName' => $queue])->get('QueueUrl'),
                'MessageGroupId' => Str::beforeLast($queue, '.fifo'),
                'MessageDeduplicationId' => md5(uniqid(rand() . time())),
                'MessageBody' => json_encode($message),
            ]);

            return $result;
        }

        return null;
    }

    private static function client(string $config): ?SqsClient
    {
        if ((bool) config("queue.connections.{$config}.key") && (bool) config("queue.connections.{$config}.secret")) {
            return new SqsClient([
                'version' => 'latest',
                'region' => config("queue.connections.{$config}.region"),
                'credentials' => new Credentials(
                    config("queue.connections.{$config}.key"),
                    config("queue.connections.{$config}.secret"),
                    config("queue.connections.{$config}.token")
                ),
            ]);
        }

        return null;
    }
}

class Queue
{
    public const ENGINE = QueueDispatch::class;

    use MessageTrait;
}
