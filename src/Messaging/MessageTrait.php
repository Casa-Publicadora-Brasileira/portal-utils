<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Messaging;

use Aws\Result;

trait MessageTrait
{
    public static function on(?string $config = null): Message
    {
        $engine = static::ENGINE;

        return new $engine($config);
    }

    public static function send(string $arn, array $message): ?Result
    {
        return self::on()->send($arn, $message);
    }

    public static function fifo(string $arn, array $message): ?Result
    {
        return self::on()->fifo($arn, $message);
    }
}
