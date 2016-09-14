<?php

class TransactionInElseCondition
{
    public function test($id)
    {
        $clients = array();
        if($id == 5) {
            $nothing = 'yes';
        } else {
            Connections::$dbConn->StartTrans();

            $sql = 'blah';
            $clients = Connections::$dbConn->GetAll($sql, array($id));

            Connections::$dbConn->CompleteTrans();
        }


        return $clients;
    }
}




