<?php

class TransactionInDoLoopWithCorrectCatch
{
    public function test($ids)
    {
        $clients = array();

        do{
            $ids--;
            $id = $ids;
            try {
                Connections::$dbConn->StartTrans();

                $sql = 'blah';
                $clients = Connections::$dbConn->GetAll($sql, array($id));

                if(!Connections::$dbConn->CompleteTrans()) {
                    $sql = false;
                }

            } catch (Exception $exception) {
                Connections::$dbConn->FailTrans();
                Connections::$dbConn->CompleteTrans();
            }
        } while($ids < 10);

        return $clients;
    }
}




