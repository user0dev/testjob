#!/usr/bin/env php

<?php

require_once("libphp-pclzip/pclzip.lib.php");

const URL_PATTERN = '/^http://[\\w\\d]+$/i';

function isValidUrl(string $url) {
    return preg_match(URL_PATTERN, $url) == 1;
}

function loadUrlFromFile(string $name)
{
    $resutl = [];
    if (!file_exists($name) && !is_readable($name)) {
        return [];
    }
    $file = fopen($name, "rt");
    if (!$file) {
        return [];
    } 
    
    for ($str = fgets($file); $str !== false; $str = fgets($file)) {
        $str = trim($str);
        if (isValidUrl($str)) {
            $result[] = $str;
        }
    }
    fclose($file);
    return result;
}


$options = getopt('f:');

//var_dump($options);
$urls = [];

if (isset($options['f'])) {
    $urls = loadUrlFromFile($options[['f']]);
}
var_dump($urls);