<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Http;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Enums\ResponseEnum;
use Illuminate\Support\Env;
use Exception;

class Response
{

    public static function success(string $msg = null, $data = null, array $params = [], bool $mobile = false): string
    {
        return self::json(true, $msg, $data, $mobile, null, $params);
    }

    public static function error(string $msg = null, Exception $exception = null, array $params = [], bool $mobile = false, int $http = HttpCodesEnum::InternalError): string
    {
        $erro = null;
        if (isset($exception)) {
            if (Env::get('APP_ENV') != 'production') {
                $erro = $exception->getMessage();
            }
            if (App()->bound('sentry') && Env::get('APP_ENV') != 'local') {
                App('sentry')->captureException($exception);
            }
        }

        return self::json(false, $msg, null, $mobile, $erro, $params, $http);
    }

    public static function warning(string $msg = null, $data = null, int $http = HttpCodesEnum::Success): string
    {
        http_response_code($http);
        return json_encode(self::cleanData(['ret' => ResponseEnum::FailedResponse, 'msg' => $msg, 'data' => $data]));
    }

    private static function json(bool $success, string $msg = null, $data = null, bool $mobile = false, string $erro = null, array $params = [], int $http = HttpCodesEnum::InternalError): string
    {
        http_response_code($http);
        if ($mobile) {
            if ($success) {
                return json_encode(self::cleanData(['success' => true, 'last_released' => self::getLastUpdatedAt($data), 'data' => $data, 'msg' => $msg] + $params));
            }
            return json_encode(self::cleanData(['success' => false, 'msg' => $msg, 'erro' => $erro] + $params), $http);
        }
        if ($success) {
            return json_encode(self::cleanData(['ret' => ResponseEnum::SuccessResponse, 'msg' => $msg, 'data' => $data] + $params));
        }
        return json_encode(self::cleanData(['ret' => ResponseEnum::FailedResponse, 'msg' => $msg, 'erro' => $erro] + $params), $http);
    }

    private static function cleanData($data): array
    {
        return array_filter($data, fn ($value, $key) => !empty($value) || $key == 'data', ARRAY_FILTER_USE_BOTH);
    }

    private static function getLastUpdatedAt($data): string
    {
        if (!is_null($data)) {
            if (is_array($data)) {
                return self::getUpdatedAtArray($data);
            }
            return self::getUpdatedAtObject($data);
        }
        return null;
    }

    private static function getUpdatedAtArray(array $data): string
    {
        if (array_key_exists('updated_at', $data)) {
            return is_null($data['updated_at']) ? date('Y-m-d H:i:s') : $data['updated_at'];
        }
        return self::getParameter('updated_at', $data);
    }

    private static function getUpdatedAtObject(object $data): string
    {
        if (array_key_exists('updated_at', $data->toArray())) {
            return is_null($data['updated_at']) ? date('Y-m-d H:i:s') : $data['updated_at'];
        }
        return self::getParameter('updated_at', $data);
    }

    private static function getParameter(string $parameter, $data): string
    {
        $last = date('Y-m-d H:i:s');
        foreach ($data as $d) {
            if (is_object($d)) {
                if (array_key_exists($parameter, $d->toArray())) {
                    if (!is_null($d[$parameter]) && $last > $d[$parameter]) {
                        $last = $d[$parameter];
                    }
                }
            } else if (is_array($d)) {
                if (array_key_exists($parameter, $d)) {
                    if (!is_null($d[$parameter]) && $last > $d[$parameter]) {
                        $last = $d[$parameter];
                    }
                }
            }
        }
        return $last;
    }
}
