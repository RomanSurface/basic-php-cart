<?php

function acmeAutoloader($class)
{
    $prefix = 'AcmeWidget';
    $class = str_replace('\\', '/', $class);
    $class = str_replace($prefix, '', $class);
    include __DIR__ . '/src/' . $class . '.php';
}
spl_autoload_register('acmeAutoloader');

