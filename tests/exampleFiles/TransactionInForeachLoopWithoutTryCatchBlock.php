<?php

class TransactionInForeachLoopWithoutTryCatchBlock
{
    public function test($ids)
    {
        $clients = array();
        foreach($ids as $is) {
            Connections::$dbConn->StartTrans();

            Connections::$dbConn->CompleteTrans();
        }

        return $clients;
    }
}




