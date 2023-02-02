# Portal Utils
 _Ferramentas genéricas do Portal da Educação da CPB_

## Requisitos

- Laravel >=7.30
- PHP >=7.4

## Instalação

```sh
composer require casa-publicadora-brasileira/portal-utils
```

## Classes

- Response

## Enums

- HttpCodesEnum
- ResponseEnum

## Exemplos

### Response

Essa biblioteca conta com três métodos públicos de resposta de API.

Sucesso:
_Tendo como todos os parâmetros opcionais._

```php
<?php

use CasaPublicadoraBrasileira\PortalUtils\HTTP\Response;

return Response::success('Sucesso ao buscar os dados', ['id' => 1, 'name' => 'Teste']);
```


Erro:
_Tendo como todos os parâmetros opcionais._

```php
<?php

use CasaPublicadoraBrasileira\PortalUtils\HTTP\Response;

catch (Exception $e) {
    return Response::error('Erro ao buscar os dados', $e);
}
```


Warning:
_Tendo como todos os parâmetros opcionais._

```php
<?php

use CasaPublicadoraBrasileira\PortalUtils\HTTP\Response;

return Response::warning('E-mail já cadastrado', null, ['email' => 'teste@teste.com'], [], 200);
```
