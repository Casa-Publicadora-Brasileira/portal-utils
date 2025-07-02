<?php

namespace CasaPublicadoraBrasileira\PortalUtils;

class Utils
{
    static function convertToDecimal(string $valor): string
    {
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);
        return $valor;
    }

    static function convertToMoney(float $valor, int $casas = 2): string
    {
        return number_format($valor, $casas, ",", ".");
    }

    /**
     * Exemplos:
     * mask($cnpj,'##.###.###/####-##');
     * mask($cpf,'###.###.###-##');
     */
    static function mask(string $val, string $mask): string
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    static function prepareSearch(string $search, bool $pre = true): string
    {
        $ex = explode(' ', $search);
        $result = $pre ? '%' : '';
        foreach ($ex as $item) {
            $result .= $item . '%';
        }
        return $result;
    }

    /** 
     * Limpa array mantendo somente os campos definidos
     * @param $array Array que será manipulado
     * @param $arFields Array contendo nome dos campos que serão mantidos
     */
    static function clearArrayIntersect(array $array, array $arFields): array
    {
        return array_intersect_key($array, array_fill_keys($arFields, 1));
    }

    /**
     * Limpa array removendo os campos informados
     * @param $array Array que será manipulado
     * @param $arFields Array contendo nome dos campos que serão removidos
     */
    static function clearArrayDiff(array $array, array $arFields): array
    {
        return array_diff_key($array, array_fill_keys($arFields, 1));
    }

    /**
     * Ordenar array multidimensional (array de array) de acordo com o campo informado
     * @param $array Array que será manipulado
     * @param $field Nome do campo que será usado para ordenar
     * @param $order Ordenação (ASC ou DESC)
     */
    static function ordernarArray(array $array, string $field, string $order = 'ASC'): array
    {
        usort($array, function ($a, $b) use ($field, $order) {
            if (strtolower($order) == 'asc') {
                return $a[$field] > $b[$field];
            } else {
                return $a[$field] < $b[$field];
            }
        });

        return $array;
    }

    /**
     * Remove os valores duplicados de um array multidimensional (array de array)
     * @param $array Array que será manipulado
     */
    static function arrayMultidimensionalUnique(array $array): array
    {
        return array_map("unserialize", array_values(array_unique(array_map("serialize", $array))));
    }

    static function debugArray(array $array): void
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        exit(0);
    }
}
