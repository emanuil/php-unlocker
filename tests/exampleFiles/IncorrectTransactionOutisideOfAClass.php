<?php

$dashId = rand();
$dashboard = new Analytics_Dashboard($dashId, true, true);

try {
    Connections::$dbConn->StartTrans();
    $dashboard->remove();
    Connections::$dbConn->CompleteTrans();
} catch (ADODB_Exception $ex) {
    Connections::$dbConn->FailTrans();
    $errors[] = $ex->getMessage();
}


