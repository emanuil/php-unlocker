<?php

class TransactionInIfForeachIfConditionWithEmptyCatch
{
    public function test($ids, $blah)
    {
        $clients = array();

        if($blah) {
            foreach($ids as $id) {
                if($id == 9) {
                    try {
                        Connections::$dbConn->StartTrans();

                        $sql = 'blah';
                        $clients = Connections::$dbConn->GetAll($sql, array($id));

                        Connections::$dbConn->CompleteTrans();


                    } catch (Exception $exception) {
                    }
                }
            }
        }



        return $clients;
    }
}




