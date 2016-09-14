<?php

class TransactionInDoWhileLoopWithoutTryCatchBlock
{
    public function test($ids)
    {
        $clients = array();

        $ids = 0;

        do {
            Connections::$dbConn->StartTrans();

            Connections::$dbConn->CompleteTrans();

            $ids++;
        } while($ids > 10);



        return $clients;
    }
}




