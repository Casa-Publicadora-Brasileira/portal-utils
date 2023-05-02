<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions;

final class Checker
{
    public static function isNull($value, $message, $args = [])
    {
        if (!is_null($value)) {
            throw new OperationException($message, $args);
        }
    }

    public static function exist($value, $message, $args = [])
    {
        if (is_null($value)) {
            throw new OperationException($message, $args);
        }
    }

    public static function notEmpty($value, $message, $args = [])
    {
        if (empty($value)) {
            throw new OperationException($message, $args);
        }
    }

    public static function isTrue($value, $message, $args = [])
    {
        if ($value == false) {
            throw new OperationException($message, $args);
        }
    }

    public static function isFalse($value, $message, $args = [])
    {
        if ($value == true) {
            throw new OperationException($message, $args);
        }
    }

    public static function equals($value, $expected, $message, $args = [])
    {
        if ($value != $expected) {
            throw new OperationException($message, $args);
        }
    }

    public static function valid($value, $predicate, $message, $args = [])
    {
        if ($predicate($value) == false) {
            throw new OperationException($message, $args);
        }
    }
}
