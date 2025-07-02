<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Sns;

use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\Sns\SnsClient;

class NotificationService
{
    public static function notify($message): ?Result
    {
        if (collect(['QUEUE_REGION', 'QUEUE_KEY', 'QUEUE_SECRET', 'QUEUE_NOTIFICATION_TOPIC'])->some(fn ($key) => empty(env($key)))) {
            return null;
        }

        $client = new SnsClient([
            'region' => env('QUEUE_REGION'),
            'credentials' => new Credentials(env('QUEUE_KEY'), env('QUEUE_SECRET')),
        ]);

        $result = $client->publish([
            'TargetArn' => env('QUEUE_NOTIFICATION_TOPIC'),
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
}
