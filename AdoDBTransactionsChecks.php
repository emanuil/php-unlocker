<?php

require 'php-parser/lib/bootstrap.php';

ini_set('xdebug.max_nesting_level', 2000);


class AdoDBTransactionsChecks {

    private $result = array();
    private $methodStatements;
    private $hasPotentialSQLInjection = false;
    private $callTree = array();


    public function checkSingleFile($fileName) {

        $result = $this->checkFile($fileName);

        print_r("\n");
        if(count($result) > 0) {

            $this->hasPotentialSQLInjection = true;
            print_r("Potential SQL lock may be caused by: $fileName\n");

            foreach ($result as $line) {
                print_r("line: $line\n");
            }

            print_r("\n");
        }

        $this->exitProperly();
    }


    public function checkFile($fileName) {

        $fileStatements = $this->parseFile($fileName);

        foreach($fileStatements as $fileStatement)
        {
            // list all the classes
            if($fileStatement->getType() == 'Stmt_Class')
            {
                foreach($fileStatement->stmts as $classStatement) {

                    // list all methods
                    if($classStatement->getType() == 'Stmt_ClassMethod') {

                        $this->methodStatements = $classStatement->stmts;

                        // list all statements from a block of code, in this case a method
                        if(is_object($classStatement) && $classStatement->stmts) {

                            //print_r($classStatement);
                            foreach($classStatement->stmts as $methodStatement) {

                                $this->callTree[] = $methodStatement;
                                $this->mainCycle($methodStatement);
                                array_pop($this->callTree);
                            }
                        }
                    }

                }
            }
            // there are no classes in the file, just plain PHP statements
            elseif(is_array($fileStatement->stmts)) {
                foreach($fileStatement->stmts as $methodStatement) {

                    // check if this is the try/catch clause
                    if($fileStatement->catches) {
                        $this->callTree[] = $fileStatement;

                    // this is for all the other clauses
                    } else {
                        $this->callTree[] = $methodStatement;
                    }

                    $this->mainCycle($methodStatement);
                    array_pop($this->callTree);
                }
            }
        }

        return $this->result;
    }


    private function findVariableByName($methodStatements, $variableName, $line) {

        // reverse the statements order so we can start from the one closer to the
        // adodb method

        foreach(array_reverse($methodStatements) as $methodStatement) {
            // we're only interested in the lines above the adodb call
            if($line > $methodStatement->getLine()) {

                $statements = $this->collectAllTheStatements($methodStatement);

                // walk through all if-else-switch statements
                foreach($statements as $statement) {
                    $result = $this->findVariableByName($statement, $variableName, $line);

                    if($result) {
                        return $result;
                    }
                }

                if(is_object($methodStatement)
                    && $methodStatement->var
                    && $methodStatement->var->name == $variableName) {
                    return $methodStatement;
                }
            }
        }

        // If we can't find how the SQL query is constructed in the current block of code
        // then flag the row for manual check. It's very difficult for simple parser
        // like this to check all the conditions of how the SQL query is constructed prior
        // to sending it to SQL
        return false;
    }


    public function checkDirectory($directoryName) {

        $path = realpath($directoryName);

        print_r("\n");
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach($objects as $name => $object) {
            if(!is_dir($name) && strstr($name, '.php') !== false) {

                $dangerousSQLQueries = $this->checkFile($name);

                if(count($dangerousSQLQueries) > 0) {

                    $this->hasPotentialSQLInjection = true;
                    print_r("Potential DB table locks can result from file $name\n");

                    foreach ($dangerousSQLQueries as $line) {
                        print_r("line: $line\n");
                    }

                    print_r("\n");
                }

                $this->result = array();
            }
        }


        $this->exitProperly();
    }


    private function parseFile($fileName)
    {
        $fileContents = file_get_contents($fileName);
        $fileStatements = array();

        try {

            $parser = new PhpParser\Parser(new PhpParser\Lexer);
            $fileStatements = $parser->parse($fileContents);
            return $fileStatements;

        } catch (PhpParser\Error $exception) {

            echo 'Parse Error: ', $exception->getMessage();
        }

        return $fileStatements;
    }


    private function checkExpressionOrAssign($methodStatement)
    {
        if(is_object($methodStatement)) {

            if ($methodStatement->getType() == 'Expr_MethodCall' && is_string($methodStatement->name) &&
                strpos(strtolower($methodStatement->name), 'starttrans') !== false
            ) {

                $completeTransFoundInCatch = false;
                $failTransFoundInCatch = false;
                $inTryCatchBlock = false;

                foreach($this->callTree as $node) {

                    if(strpos($node->getType(), 'Stmt_TryCatch') !== false) {

                        $inTryCatchBlock = true;

                        $completeTransFoundInTry = $this->checkIfCompleteTransIsCalled($node);
                        $completeTransFoundInCatch = $this->checkIfCompleteTransIsCalled($node->catches[0]);

                        foreach($node->catches[0]->stmts as $catchStatement) {
                            if(strpos($catchStatement->name, 'FailTrans') !== false) {
                                $failTransFoundInCatch = true;
                            }
                        }
                    }
                }

                if(!$inTryCatchBlock || !$completeTransFoundInCatch || !$failTransFoundInCatch || !$completeTransFoundInTry) {
                    $this->result[] = $methodStatement->getLine();
                }

            }
        }
    }


    private function mainCycle($classStatement)
    {
        if($classStatement->getType() != 'Expr_MethodCall'
            && $classStatement->getType() != 'Expr_Assign') {

            $this->checkNonAssignAndMethodCallStatements($classStatement);

            // The ternary operator
        }elseif($classStatement->getType() == 'Expr_Assign'
            && $classStatement->expr->getType() == 'Expr_Ternary') {

            $this->checkTernaryOperator($classStatement);
        }
        else {
            $this->checkExpressionOrAssign($classStatement);
        }

    }



    private function checkTheIfStatement($methodStatement)
    {
        if ($methodStatement->else) {
            foreach($methodStatement->else->stmts as $statement) {
                $this->callTree[] = $statement;
                $this->mainCycle($statement);
                array_pop($this->callTree);
            }

        }

        if ($methodStatement->elseifs) {
            foreach($methodStatement->elseifs[0]->stmts as $statement) {
                $this->callTree[] = $statement;
                $this->mainCycle($statement);
                array_pop($this->callTree);
            }
        }


        if ($methodStatement->cond->right) {
            $this->checkExpressionOrAssign($methodStatement->cond->right);
        }

        if ($methodStatement->cond->left) {
            $this->checkExpressionOrAssign($methodStatement->cond->left);
        }


        if (is_object($methodStatement->cond->left) && $methodStatement->cond->left->getType() == 'Expr_FuncCall') {

            if ($methodStatement->cond->left->args) {
                $this->checkExpressionOrAssign($methodStatement->cond->left->args[0]->value);
            }
        }
    }


    private function collectAllTheStatements($methodStatement)
    {
        $statements = array();

        if (!empty($methodStatement->stmts)) {

            $statements[] = $methodStatement->stmts;
        }

        if (!empty($methodStatement->cases)) {
            $statements[] = $methodStatement->cases;
        }


        if (!empty($methodStatement->else)) {
            $statements[] = $methodStatement->else->stmts;
        }

        if (is_object($methodStatement->elseifs)) {
            $statements[] = $methodStatement->elseifs->stmts;
        }

        if (isset($methodStatement->elseifs[0])) {
            $statements[] = $methodStatement->elseifs[0]->stmts;
        }
        return $statements;
    }


    private function checkForSafeComments($methodStatement)
    {
        if (is_array($methodStatement->getAttribute('comments'))) {

            foreach ($methodStatement->getAttribute('comments') as $comment) {

                $reflector = new \ReflectionClass($comment);
                $classProperty = $reflector->getProperty('text');
                $classProperty->setAccessible(true);
                $commentText = $classProperty->getValue($comment);

                if (strpos(strtolower($commentText), 'safesql') !== false) {
                    return true;
                }
            }
        }
    }



    private function checkSwitchStatement($methodStatement)
    {
        foreach ($methodStatement->cases as $switchCase) {
            foreach($switchCase->stmts as $statement) {
                $this->mainCycle($statement);
            }

        }
    }


    private function checkTernaryOperator($methodStatement)
    {
        $this->checkExpressionOrAssign($methodStatement->expr->if);
        $this->checkExpressionOrAssign($methodStatement->expr->else);
    }


    private function checkNonAssignAndMethodCallStatements($methodStatement)
    {
        // Drill deep down if there are more statements
        if ($methodStatement->stmts) {

            foreach($methodStatement->stmts as $statement) {
                $this->callTree[] = $statement;
                $this->mainCycle($statement);
                array_pop($this->callTree);
            }


        }

        if ($methodStatement->getType() == 'Stmt_If') {
            $this->checkTheIfStatement($methodStatement);
        }

        // The switch statements
        if ($methodStatement->getType() == 'Stmt_Switch') {
            $this->checkSwitchStatement($methodStatement);
        }

        // The sql method is in the returned directly: e.g. return GetAll(...);
        if (is_object($methodStatement->expr) && $methodStatement->getType() == 'Stmt_Return') {
            $this->checkExpressionOrAssign($methodStatement->expr);
        }
    }

    // this is needed so the CI can pick the failure and fail the job
    protected function exitProperly()
    {
        if ($this->hasPotentialSQLInjection) {
            exit(-1);
        } else {
            exit(0);
        }
    }


    private function checkIfCompleteTransIsCalled($node)
    {
        $completeTransFoundInTry = false;

        foreach ($node->stmts as $tryStatement) {

            // handle method call only - CompleteTrans()
            if (strpos(strtolower($tryStatement->name), 'completetrans') !== false) {
                return true;
            }
            // handle assignment, e.g. $result = CompleteTrans()
            if (is_object($tryStatement->expr)) {
                if (strpos(strtolower($tryStatement->expr->name), 'completetrans') !== false) {
                    return true;
                }
            }

            // handle if case, e.g. if(CompleteTrans()){}
            if (strpos($tryStatement->getType(), 'Stmt_If') !== false) {

                if (strpos(strtolower($tryStatement->cond->name), 'completetrans') !== false) {
                    return true;
                }

                // handle if case negate, e.g. if(!CompleteTrans()){}
                if (is_object($tryStatement->cond->expr)) {
                    if (strpos(strtolower($tryStatement->cond->expr->name), 'completetrans') !== false) {
                        return true;
                    }
                }

                // there are more than one statements in the if block, recursively check them all
                if (is_array($tryStatement->stmts)) {
                    $completeTransFoundInTry = $this->checkIfCompleteTransIsCalled($tryStatement);
                }
            }

        }
        return $completeTransFoundInTry;
    }
}