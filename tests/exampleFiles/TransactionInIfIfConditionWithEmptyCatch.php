<?php

class TransactionInIfIfConditionWithEmptyCatch
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

                    Connections::$dbConn->CompleteTrans();


                } catch (Exception $exception) {
                }
            }
        }


        return $clients;
    }
}




