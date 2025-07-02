<?php

namespace CasaPublicadoraBrasileira\PortalUtils;

class JsonValidator
{
    /**
     * Validates a json string against a given structure. The format of the structure is the same as assertJsonStructure() uses.
     * This function will return false is there are unexpected elements in the data
     * If used in strict mode, it will also return false if elements are missing in the data
     *
     * @see https://laravel.com/docs/8.x/http-tests#assert-json-structure

     * @param array $structure
     * @param string $json
     * @param bool $strict
     * @return bool
     */
    public static function validateJson(array $structure, string $json, bool $strict = false): bool
    {
        $json_as_array = json_decode($json, true);

        return self::validateArrayStructure($structure, $json_as_array, $strict);
    }

    /**
     * Recursively validates an array ($data) against a given structure.
     * This function will return false is there are unexpected elements in the data
     * If used in strict mode, it will also return false if elements are missing in the data
     *
     * @param array $structure
     * @param array $data
     * @param bool $strict
     * @return bool
     */
    public static function validateArrayStructure(array $structure, array $data, bool $strict = false): bool
    {
        //First check the array keys at the main level
        if (!self::arrayKeysExist($structure, $data, $strict)) {
            return false;
        }

        foreach ($data as $sub_key => $sub_data) {
            if (is_array($sub_data)) {
                //If we find an array, it could be a sub structure or an array of sub structures

                if (isset($structure['*'])) {
                    //If the structure indicates a '*', this is an array of sub structures.
                    //Because the data is an anonymous array, we use the '*' as key ($sub_key doesn't point to a valid key in the structure)
                    $sub_structure = $structure['*'];
                } elseif (!array_key_exists($sub_key, $structure)){
                    //This is a key we didn't expect
                    return false;
                } else {
                    //This is just a normal sub structure
                    $sub_structure = $structure[$sub_key];
                }

                if (!self::validateArrayStructure($sub_structure, $sub_data, $strict)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks if keys of the data array match the structure. If extra keys are found in the data, it returns false
     * If used in strict mode, it will also return false if keys in de data are missing.
     *
     * @param array $structure
     * @param array $data
     * @param bool $strict
     * @return bool
     */
    public static function arrayKeysExist(array $structure, array $data, bool $strict = false): bool
    {
        //If the structure is an empty array ('key' => []) and we have a one dimensional array, this is considered the same
        if (count($structure) == 0 && (count($data) == count($data, COUNT_RECURSIVE))) {
            return true;
        }

        // If the structure indicates a '*' then the first element in the data array should also be an array. If not, it's considered missing
        if (isset($structure['*'])) {
            return (is_array($data[0]));
        }

        //If there is a sub structure, we need the key, else the value
        // [ 'a', 'b', 'c' => 'd'] then we need [ 'a', 'b' and 'c' ]
        // array_keys() will return [ 0, 1, 'c' ] so that doesn't work
        $fake_structure = [];
        foreach($structure as $key => $value) {
            if (is_array($value)) {
                $fake_structure[$key] = $key;
            } else {
                $fake_structure[$value] = $value;
            }
        }

        $data_collection = collect($data);
        $structure_collection = collect($fake_structure);

        $diff_for_more = $data_collection->diffKeys($structure_collection);
        $diff_for_less = $structure_collection->diffKeys($data_collection);

        return (count($diff_for_more) == 0 && (!$strict || count($diff_for_less) == 0));
    }
}