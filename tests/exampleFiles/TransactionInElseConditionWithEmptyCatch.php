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
                $id = 0;
                $clients = array(1);
            }
        }

        return $clients;
    }
}




