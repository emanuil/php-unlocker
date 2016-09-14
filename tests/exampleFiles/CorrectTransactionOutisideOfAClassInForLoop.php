<?php

$dashId = rand();
$dashboard = new Analytics_Dashboard($dashId, true, true);

foreach ($userDashboarIds as $dashId)
{
    try
    {
        Connections::$dbConn->StartTrans();
        $dashboard->remove();
        Connections::$dbConn->CompleteTrans();
    } catch (ADODB_Exception $ex) {
        Connections::$dbConn->FailTrans();
        Connections::$dbConn->CompleteTrans();
        $errors[] = $ex->getMessage();
    }
}



