<?php

require_once '../AdoDBTransactionsChecks.php';


class AdoDBTransactionsTests extends PHPUnit_Framework_TestCase {

    function testTransactionInMethodBodyWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInMethodBodyWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(7));
    }

    function testNoTransactionInMethodBody() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/NoTransactionInMethodBody.php');

        $this->assertEquals($result, array());
    }

    function testTransactionInMethodBodyOutsideTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInMethodBodyOutsideTryCatchBlock.php');

        $this->assertEquals($result, array(13));
    }

    function testTransactionInIfConditionWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfConditionWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(12));
    }

    function testTransactionInElseConditionWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInElseConditionWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(11));
    }

    function testTransactionInNestedIfConditionWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInNestedIfConditionWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(12));
    }

    function testTransactionInForeachLoopWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInForeachLoopWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(9));
    }

    function testTransactionInForeachIfConditionWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInForeachIfConditionWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(11));
    }

    function testTransactionInIfForeachIfConditionWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfForeachIfConditionWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(13));
    }

    function testTransactionInDoWhileLoopWithoutTryCatchBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInDoWhileLoopWithoutTryCatchBlock.php');

        $this->assertEquals($result, array(12));
    }

    function testTransactionInMethodBodyWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInMethodBodyWithEmptyCatch.php');

        $this->assertEquals($result, array(10));
    }


    function testTransactionInMethodBodyWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInMethodBodyWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }


    function testTransactionInMethodBodyWithNoCompleteTransInBody() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInMethodBodyWithNoCompleteTransInBody.php');

        $this->assertEquals($result, array(11));
    }

    function testTransactionInMethodBodyWithNoFailTransInBody() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInMethodBodyWithNoFailTransInBody.php');

        $this->assertEquals($result, array(11));
    }

    function testTransactionInIfConditionWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfConditionWithEmptyCatch.php');

        $this->assertEquals($result, array(11));
    }

    function testTransactionInIfConditionWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfConditionWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }

    function testTransactionInElseConditionWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInElseConditionWithEmptyCatch.php');

        $this->assertEquals($result, array(14));
    }

    function testTransactionInElseConditionWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInElseConditionWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }

    function testTransactionInForeachLoopWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInForeachLoopWithEmptyCatch.php');

        $this->assertEquals($result, array(11));
    }

    function testTransactionInForeachLoopWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInForeachLoopWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }

    function testTransactionInDoLoopWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInDoLoopWithEmptyCatch.php');

        $this->assertEquals($result, array(13));
    }

    function testTransactionInDoLoopWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInDoLoopWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }


    function testTransactionInIfIfConditionWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfIfConditionWithEmptyCatch.php');

        $this->assertEquals($result, array(12));
    }

    function testTransactionInIfIfConditionWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfIfConditionWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }

    function testTransactionInForeachIfConditionWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInForeachIfConditionWithEmptyCatch.php');

        $this->assertEquals($result, array(12));
    }

    function testTransactionInForeachIfConditionWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInForeachIfConditionWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }

    function testTransactionInIfForeachIfConditionWithEmptyCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfForeachIfConditionWithEmptyCatch.php');

        $this->assertEquals($result, array(13));
    }

    function testTransactionInIfForeachIfConditionWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInIfForeachIfConditionWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }

    function testTransactionInMethodBodyWithNoCompleteTransInTryBlock() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInMethodBodyWithNoCompleteTransInTryBlock.php');

        $this->assertEquals($result, array(10));
    }

    function testTransactionInTryTryBlockWithNoCompleteTransInTheSecondTry() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInTryTryBlockWithNoCompleteTransInTheSecondTry.php');

        $this->assertEquals($result, array(11));
    }

    function testCorrectTransactionOutsideOfAClass() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/CorrectTransactionOutisideOfAClass.php');

        $this->assertEquals($result, array());
    }

    function testCorrectTransactionOutsideOfAClassInForLoop() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/CorrectTransactionOutisideOfAClassInForLoop.php');

        $this->assertEquals($result, array());
    }


    function testIncorrectTransactionOutsideOfAClass() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/IncorrectTransactionOutisideOfAClass.php');

        $this->assertEquals($result, array(7));
    }

    function testIncorrectTransactionOutsideOfAClassInForLoop() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/IncorrectTransactionOutisideOfAClassInForLoop.php');

        $this->assertEquals($result, array(8));
    }

    function testTransactionInTryIfConditionWithCorrectCatch() {
        $checks = new AdoDBTransactionsChecks();

        $result = $checks->checkFile('exampleFiles/TransactionInTryIfConditionWithCorrectCatch.php');

        $this->assertEquals($result, array());
    }

}