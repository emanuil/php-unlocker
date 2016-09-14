<?php

class TransactionInMethodBody
{
    public function test($id)
    {
        Connections::$dbConn->StartTrans();

        $sql = 'blah';
        $clients = Connections::$dbConn->GetAll($sql, array($id));

        Connections::$dbConn->CompleteTrans();

        return $clients;
    }
}




