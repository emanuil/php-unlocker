<?php

class TransactionInMethodBody
{
    public function test($id)
    {

        $id = 'blah';

        return $id;
    }
}




