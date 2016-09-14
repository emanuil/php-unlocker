<?php

class TransactionInIfCondition
{
    public function test($id)
    {

        $clients = array();

        if($id) {

            Connections::$dbConn->StartTrans();


            Connections::$dbConn->FailTrans();
            Connections::$dbConn->CompleteTrans();

        }

        return $clients;
    }
}




