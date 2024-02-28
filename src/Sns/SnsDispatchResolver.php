<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Sns;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;

class SnsDispatchResolver {

    public static function send(string $targetArn, array $message): \Aws\Result
    {
        $client = new SnsClient([
            'region'      => config('services.sns.region'),
            'credentials' => new Credentials(
                config('services.sns.key'),
                config('services.sns.secret')
            ),
        ]);

        $result = $client->publish([
            'TargetArn' => $targetArn,
            'Message' => json_encode($message),
            'MessageGroupId' => 1,
        ]);

        return $result;
    }
}