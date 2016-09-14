<?php

class TransactionInForeachLoopWithEmptyCatch
{
    public function test($ids)
    {
        $clients = array();

        foreach($ids as $id) {
            try {
                Connections::$dbConn->StartTrans();

                $sql = 'blah';
                $clients = Connections::$dbConn->GetAll($sql, array($id));

                Connections::$dbConn->CompleteTrans();


            } catch (Exception $exception) {
            }
        }

        return $clients;
    }
}




