<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Messaging;

use Aws\Result;

interface Message
{
    public function send(?string $key, array $message): ?Result;

    public function fifo(?string $key, array $message): ?Result;
}
