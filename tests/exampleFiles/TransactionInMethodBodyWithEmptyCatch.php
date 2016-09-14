<?php

class TransactionInMethodBodyWithEmptyCatch
{
    public function test($id)
    {
        $clients = array();

        try {
            Connections::$dbConn->StartTrans();

            $sql = 'blah';
            $clients = Connections::$dbConn->GetAll($sql, array($id));

            Connections::$dbConn->CompleteTrans();


        } catch (Exception $exception) {

        }

        return $clients;
    }
}




