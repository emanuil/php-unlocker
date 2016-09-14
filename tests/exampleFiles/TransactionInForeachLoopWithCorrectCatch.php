<?php

class TransactionInForeachLoopWithCorrectCatch
{
    public function test($ids)
    {
        $clients = array();

        foreach($ids as $id) {
            try {
                Connections::$dbConn->StartTrans();

                $sql = 'blah';
                $clients = Connections::$dbConn->GetAll($sql, array($id));
                $result = Connections::$dbConn->CompleteTrans();


            } catch (Exception $exception) {
                Connections::$dbConn->FailTrans();
                Connections::$dbConn->CompleteTrans();
            }
        }

        return $clients;
    }
}




