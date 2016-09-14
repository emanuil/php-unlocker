<?php

class TransactionInDoLoopWithEmptyCatch
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

                Connections::$dbConn->CompleteTrans();


            } catch (Exception $exception) {
            }
        } while($ids < 10);

        return $clients;
    }
}




