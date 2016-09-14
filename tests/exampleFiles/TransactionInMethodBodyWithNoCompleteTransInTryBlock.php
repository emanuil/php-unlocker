<?php

class TransactionInMethodBodyWithNoCompleteTransInTryBlock
{
    public function test($id)
    {
        $clients = array();

        try {
            Connections::$dbConn->StartTrans();

            $sql = 'blah';
            $clients = Connections::$dbConn->GetAll($sql, array($id));

        } catch (Exception $exception) {

            Connections::$dbConn->FailTrans();
            Connections::$dbConn->CompleteTrans();
        }

        return $clients;
    }
}




