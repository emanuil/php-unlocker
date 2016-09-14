<?php

class TransactionInForeachIfConditionWithCorrectCatch
{
    public function test($ids)
    {
        $clients = array();

        foreach($ids as $id) {
            if($id == 9) {
                try {
                    Connections::$dbConn->StartTrans();

                    $sql = 'blah';
                    $clients = Connections::$dbConn->GetAll($sql, array($id));

                    Connections::$dbConn->CompleteTrans();


                } catch (Exception $exception) {
                    Connections::$dbConn->FailTrans();
                    Connections::$dbConn->CompleteTrans();
                }
            }
        }


        return $clients;
    }
}




