<?php

class TransactionInIfConditionWithCorrectCatch
{
    public function test($id)
    {
        $clients = array();

        if($id == 9) {
            try {
                Connections::$dbConn->StartTrans();

                $sql = 'blah';
                $clients = Connections::$dbConn->GetAll($sql, array($id));

                if(Connections::$dbConn->CompleteTrans()) {
                    $sql = true;
                }

            } catch (Exception $exception) {
                Connections::$dbConn->FailTrans();
                Connections::$dbConn->CompleteTrans();
            }
        }

        return $clients;
    }
}




