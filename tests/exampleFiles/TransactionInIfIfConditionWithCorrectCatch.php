<?php

class TransactionInIfIfConditionWithCorrectCatch
{
    public function test($id)
    {
        $clients = array();

        if($id > 5){
            if($id == 9) {
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
        }


        return $clients;
    }
}




