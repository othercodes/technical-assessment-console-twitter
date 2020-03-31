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

if (!function_exists('uuid')) {

    /**
     * Generate a new UUID.
     * @return string
     */
    function uuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }
}

if (!function_exists('quote')) {

    /**
     * Add double quotes to the string.
     * @param string $string
     * @return string
     */
    function quote(string $string): string
    {
        return '"' . $string . '"';
    }
}
