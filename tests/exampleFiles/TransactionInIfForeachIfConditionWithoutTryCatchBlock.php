<?php

class TransactionInIfForeachIfConditionWithoutTryCatchBlock
{
    public function test($ids)
    {
        $clients = array();

        if($clients > 5) {
            foreach($ids as $id) {

                if($id > 0) {
                    Connections::$dbConn->StartTrans();

                    Connections::$dbConn->CompleteTrans();
                }

            }
        }


        return $clients;
    }
}




