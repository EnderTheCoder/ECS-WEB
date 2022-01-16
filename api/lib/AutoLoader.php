<?php
spl_autoload_register(function ($name) {
    if (file_exists("./lib/" . $name . ".php")) require_once "./lib/" . $name . ".php";
    if (file_exists("./executor/" . $name . ".php")) require_once "./executor/" . $name . ".php";
    if (file_exists("./interface/" . $name . ".php")) require_once "./interface/" . $name . ".php";
});


require_once "./lib/Tools.php";

header('Access-Control-Allow-Methods:POST');
header("Content-type: application/json; charset=utf-8");
