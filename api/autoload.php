<?php

function __autoload($class){
    include_once "core/utils/{$class}.php";
    include_once "core/interface/{$class}.php";
    include_once "core/config/{$class}.php";
    include_once "core/libs/personal/{$class}.php";
    include_once "core/controller/{$class}.php";
    include_once "core/bean/{$class}.php";
    include_once "core/dao/{$class}.php";
}

error_reporting(E_ERROR | E_PARSE);

// Setting all necessary headers
//Headers::getAllHeaders();