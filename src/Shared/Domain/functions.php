<?php

if (!function_exists('arrayize')) {

    /**
     * Transform the given structure into array.
     * @param mixed $source
     * @return array
     */
    function arrayize($source): array
    {
        $array = [];
        foreach ($source as $key => $item) {
            switch (gettype($item)) {
                case 'object':
                case 'array':
                    $buffer = arrayize($item);
                    if (!empty($buffer)) {
                        $array[trim($key)] = $buffer;
                    }
                    break;
                default:
                    if (isset($item)) {
                        $array[trim($key)] = $item;
                    }
            }
        }

        return $array;
    }
}