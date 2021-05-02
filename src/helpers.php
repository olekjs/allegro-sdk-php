<?php

if (!function_exists('config')) {
    function config($key, $default = null)
    {
        if ($key === null) {
            return $default;
        }

        $explodedKey    = explode('.', $key);
        $configFileName = $explodedKey[0];

        $configContents = include __DIR__ . '/config/' . $configFileName . '.php';

        unset($explodedKey[0]);

        return array_get($configContents, implode('.', $explodedKey));
    }
}

if (!function_exists('array_accessible')) {
    function array_accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}

if (!function_exists('array_string_key_exists')) {
    function array_string_key_exists($array, $key)
    {
        if ($array instanceof Enumerable) {
            return $array->has($key);
        }

        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}

if (!function_exists('array_get')) {
    function array_get($array, $key, $default = null)
    {
        if (!array_accessible($array)) {
            return $default;
        }

        if (is_null($key)) {
            return $array;
        }

        if (array_string_key_exists($array, $key)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (array_accessible($array) && array_string_key_exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }
}

if (!function_exists('base64_url_encode')) {
    function base64_url_encode($plainText)
    {
        $base64    = base64_encode($plainText);
        $base64    = trim($base64, '=');
        $base64url = strtr($base64, '+/', '-_');

        return ($base64url);
    }
}

if (!function_exists('dd')) {
    function dd($variable)
    {
        foreach (func_get_args() as $variable) {
            var_dump($variable);
        }
        die;
    }
}
