<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions;

use RuntimeException;

final class OperationException extends RuntimeException
{
    protected string $key;
    protected array $args;

    public function __construct($key, $args)
    {
        $this->key = $key;
        $this->args = $args;

        parent::__construct($key, 0, null);
    }

    final public function getArgs()
    {
        return $this->args;
    }

    final public function getKey()
    {
        return $this->key;
    }
}
