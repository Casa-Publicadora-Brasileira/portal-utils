<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Sns;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;

class SnsDispatchResolver {

    public function __construct(string $targetArn, array $message)
    {
        $client = new SnsClient([
            'region'      => config('services.sns.region'),
            'credentials' => new Credentials(
                config('services.sns.key'),
                config('services.sns.secret')
            ),
        ]);

        $json = json_encode([
                'type' => 'COMMUNICATION',
                'data' => [
                    'id' => '1',
                    'name'  => 'Teste134',
                ]
            ]
        );

        $client->publish([
            'TargetArn' => $targetArn,
            'Message' => json_encode($message),
            'MessageGroupId' => 1,
        ]);
    }
}