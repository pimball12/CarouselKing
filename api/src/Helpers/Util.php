<?php

namespace Src\Helpers;

class Util
{
    public static function fd(mixed $data, bool $die = false): void
    {
        echo "<pre style='background-color:black; color:white; padding:15px; border-radius: 5px; margin:10px 0px'>" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

        if ($die) {

            die();
        }
    }

    public static function path($path)
    {
        $parts = explode('/', str_replace('\\', '/', $path));
        $absolutes = [];

        foreach ($parts as $part) {

            if ($part === '' || $part === '.') continue;

            if ($part === '..') {

                array_pop($absolutes);
            } else {

                $absolutes[] = $part;
            }
        }

        $prefix = (substr($path, 0, 1) === '/' || preg_match('/^[A-Z]:/i', $path)) ? '/' : '';
        return $prefix . implode('/', $absolutes);
    }

    public static function validJson(string $string): bool
    {
        if (!is_string($string)) {

            return false;
        }

        $string = trim($string);

        if ($string === '' || ($string[0] !== '{' && $string[0] !== '[')) {
            
            return false;
        }

        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function toSingular(string $word): string
    {
        $irregulars = [

            'children' => 'child',
            'men' => 'man',
            'women' => 'woman',
            'mice' => 'mouse',
            'geese' => 'goose',
            'feet' => 'foot',
            'teeth' => 'tooth',
            'people' => 'person',
        ];
    
        if (isset($irregulars[strtolower($word)])) {

            return $irregulars[strtolower($word)];
        }
    
        if (preg_match('/(oes)$/', $word)) {

            return preg_replace('/(oes)$/', 'o', $word);
        }
    
        if (preg_match('/(ies)$/', $word)) {

            return preg_replace('/(ies)$/', 'y', $word);
        }
    
        if (preg_match('/(ves)$/', $word)) {

            return preg_replace('/(ves)$/', 'f', $word);
        }
    
        if (preg_match('/(ses|xes|zes|ches|shes)$/', $word)) {

            return substr($word, 0, -2); 
        }
    
        if (substr($word, -1) === 's') {

            return substr($word, 0, -1);
        }
    
        return $word;
    }
    
}
