<?php

use CasaPublicadoraBrasileira\PortalUtils\Utils;

function mask(string $val, string $mask): string
{
    return Utils::mask($val, $mask);
}
