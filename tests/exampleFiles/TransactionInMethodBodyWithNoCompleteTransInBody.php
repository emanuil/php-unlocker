<?php

class TransactionInMethodBodyWithNoCompleteTransInBody
{
    public function test($id)
    {
        $clients = array();

        Connections::$dbConn->CompleteTrans();
        try {
            Connections::$dbConn->StartTrans();

            $sql = 'blah';
            $clients = Connections::$dbConn->GetAll($sql, array($id));

            Connections::$dbConn->CompleteTrans();


        } catch (Exception $exception) {

            Connections::$dbConn->FailTrans();

        }

        Connections::$dbConn->CompleteTrans();
        return $clients;
    }
}




