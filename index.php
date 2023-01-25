<?php

mb_internal_encoding('UTF-8');

/**
 * Autoloads files so you don't have to require every file manually
 */
spl_autoload_register(function (string $class) {
    if (str_ends_with($class, "Presenter"))
        require("presenter/" . $class . ".php");
    else
        require("model/" . $class . ".php");
});

$router = new RouterPresenter();
$router->process(array($_SERVER['REQUEST_URI']));

$router->writeView();