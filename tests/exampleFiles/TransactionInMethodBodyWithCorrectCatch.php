<?php

class TransactionInMethodBodyWithCorrectCatch
{
    public function test($id)
    {
        $clients = array();

        try {
            Connections::$dbConn->StartTrans();

            $sql = 'blah';
            $clients = Connections::$dbConn->GetAll($sql, array($id));
            return Connections::$dbConn->CompleteTrans();

        } catch (Exception $exception) {

            Connections::$dbConn->FailTrans();
            Connections::$dbConn->CompleteTrans();
        }

        return $clients;
    }
}




