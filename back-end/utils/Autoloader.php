<?php

function __autoload($class){
    $elements = explode('_', $class);
    $path = implode(DIRECTORY_SEPARATOR, $elements);
    require_once($path . '.php');
}

?>