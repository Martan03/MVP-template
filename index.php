<?php

mb_internal_encoding('UTF-8');

spl_autoload_register(function (string $class) {
    if (str_ends_with($class, "Presenter"))
        require("presenter/" . $class . ".php");
    else
        require("model/" . $class . ".php");
});

$router = new RouterPresenter();
$router->process(array($_SERVER['REQUEST_URI']));

$router->writeView();