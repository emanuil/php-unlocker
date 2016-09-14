<?php

class TransactionInElseConditionWithEmptyCatch
{
    public function test($id)
    {
        $clients = array();

        if($id == 9) {

        }
        else {
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

        return $clients;
    }
}




