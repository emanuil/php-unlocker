<?php

class TransactionInTryTryBlockWithNoCompleteTransInTheSecondTry
{
    public function test($ids, $sql)
    {
        $clients = array($ids);

        try {
            try {
                Connections::$dbConn->StartTrans();
                $clients = Connections::$dbConn->GetAll($sql, array($ids));

            } catch(Exception $e) {

            }
        } catch(Exception $e) {

        }

        return $clients;
    }
}




