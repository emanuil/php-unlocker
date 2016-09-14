<?php

class TransactionInNestedIfConditionWithoutTryCatchBlock
{
    public function test($id, $test)
    {
        $clients = array();

        if($id) {

            if($test) {
                Connections::$dbConn->StartTrans();

                Connections::$dbConn->CompleteTrans();
            }


        }

        return $clients;
    }
}




