<?php

use CasaPublicadoraBrasileira\PortalUtils\Utils;

function mask(string $val, string $mask): string
{
    return Utils::mask($val, $mask);
}

function safeArray(array $array): mixed
{
    if (in_array(null, $array, true)) {
        return null;
    }

    return $array;
}
