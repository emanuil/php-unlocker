<?php

class TransactionInMethodBodyWithNoFailTransInBody
{
    public function test($id)
    {
        $clients = array();

        Connections::$dbConn->FailTrans();
        try {
            Connections::$dbConn->StartTrans();

            $sql = 'blah';
            $clients = Connections::$dbConn->GetAll($sql, array($id));

            Connections::$dbConn->FailTrans();

        } catch (Exception $exception) {

            Connections::$dbConn->CompleteTrans();
        }

        Connections::$dbConn->FailTrans();
        return $clients;
    }
}




