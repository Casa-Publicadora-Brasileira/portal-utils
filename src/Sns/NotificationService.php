<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Sns;

use Aws\Result;
use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;

class NotificationService
{
    public static function notify($message): Result
    {
        $client = new SnsClient([
            'region'      => env('QUEUE_REGION'),
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
            ]
        ]);
        return $result;
    }
}
