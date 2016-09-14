<?php

class TransactionInTryIfConditionWithCorrectCatch
{
    public function test($id)
    {
        $clients = array();

        try {
            if($id == 9) {
                $sql = 'blah';

                Connections::$dbConn->StartTrans();
                Connections::$dbConn->GetAll($sql, array($id));
                Connections::$dbConn->CompleteTrans();
            }

        } catch (Exception $exception) {
            Connections::$dbConn->FailTrans();
            Connections::$dbConn->CompleteTrans();
        }


        return $clients;
    }
}




