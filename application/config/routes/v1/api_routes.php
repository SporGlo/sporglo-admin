<?php
defined('BASEPATH') or exit('No direct script access allowed');

function beautify($array, $exit = false)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    if ($exit)
        die;
}

// API Version Prefix
$api_version = 'api/v1';

// Define resources
$resources = ['sports','position','admin','staff','login'];

foreach ($resources as $resource) {
    // List all
    $route["$api_version/$resource"] = "$resource/list";

    // Get details
    $route["$api_version/$resource/(:num)"] = "$resource/details/$1";

    // Create new
    $route["$api_version/$resource/new"] = "$resource/new";

    // Update existing
    $route["$api_version/$resource/update/(:num)"] = "$resource/update/$1";

    // Delete   
    $route["$api_version/$resource/delete/(:num)"] = "$resource/delete/$1";

    // $route["$api_version/$resource/"] = "$resource/report";




}

$route['api/v1/auth/login'] = 'login/validate';
$route['api/v1/auth/logout'] = 'login/logout';
