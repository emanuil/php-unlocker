<?php

$dashId = rand();
$dashboard = new Analytics_Dashboard($dashId, true, true);

try {
    Connections::$dbConn->StartTrans();
    $dashboard->remove();
    $status = Connections::$dbConn->CompleteTrans();

} catch (ADODB_Exception $ex) {
    Connections::$dbConn->FailTrans();
    $status = Connections::$dbConn->CompleteTrans();
    $errors[] = $ex->getMessage();
}




