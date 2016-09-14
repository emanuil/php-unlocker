<?php

class TransactionInMethodBody
{
    public function test($id)
    {
        try {
            $clients = $id;
        } catch(Exception $exception) {
            $error = $exception->getCode();
        }

        Connections::$dbConn->StartTrans();

        $sql = 'blah';
        $clients = Connections::$dbConn->GetAll($sql, array($id));

        Connections::$dbConn->CompleteTrans();

        return $clients;
    }
}




