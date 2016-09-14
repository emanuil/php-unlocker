<?php

class TransactionInForeachIfConditionWithoutTryCatchBlock
{
    public function test($ids)
    {
        $clients = array();
        foreach($ids as $id) {

            if($id > 0) {
                Connections::$dbConn->StartTrans();

                Connections::$dbConn->CompleteTrans();
            }

        }

        return $clients;
    }
}




