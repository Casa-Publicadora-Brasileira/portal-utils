<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Messaging;

trait MessageTrait
{
    public static function on(string $config = 'sns'): Message
    {
        $engine = static::ENGINE;

        return new $engine($config);
    }

    public static function send(string $arn, array $message): \Aws\Result
    {
        return self::on()->send($arn, $message);
    }

    public static function fifo(string $arn, array $message): \Aws\Result
    {
        return self::on()->fifo($arn, $message);
    }
}
