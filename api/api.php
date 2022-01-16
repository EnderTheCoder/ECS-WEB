<?php

require "./lib/AutoLoader.php";
require "./lib/DebugON.php";

$EXECUTOR = null;
$dataSender = new DataSender();


switch ($_GET['executor']) {
    case 'register': {
        $EXECUTOR = new RegisterExecutor();
        break;
    }
    default: {
        $dataSender->easyRun("emptyParam");
    }
}

//var_dump($_POST);

if ($EXECUTOR->checkFields()) {
    $EXECUTOR->run();
    $EXECUTOR->data();
} else {
    $dataSender->easyRun("emptyParam");
}