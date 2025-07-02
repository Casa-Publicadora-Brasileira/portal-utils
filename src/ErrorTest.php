<?php

namespace CasaPublicadoraBrasileira\PortalUtils;

use CasaPublicadoraBrasileira\PortalUtils\Exceptions\Checker;
use PHPUnit\Framework\TestCase;

// Comendo para testar
// ./vendor/bin/phpunit src/ErrorTest.php

final class ErrorTest extends TestCase
{
    public function test_class_constructor()
    {

        Checker::isTrue(userid == bancouserid, 'ouser');
    }
}
