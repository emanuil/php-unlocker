PHP-Unlocker
==========
PHP tool to scan [ADOdb](http://adodb.sourceforge.net) code for unintended table locks related to database transactions

Why
===
The main idea is to be able to detect problems as early as possible, when the code is fresh in your mind. Shift as much checks as possible to the left. Automate as much as possible. 

PHP-Unlocker statically analyzes your ADOdb PHP code in order to detect if there are potentially unwanted table locks related to db transactions. This kind of problem is easy to detect with static code analysis. Check the examples section bellow more more infotmation.  

You'll get the most out of PHP-Unlocker if you run it on every commit. It's made to be CI friendly and fast.


Examples
========

Imagine the following method:

```php
public function someMethod($argument) {
        
    Connections::$dbConn->StartTrans();
    
    $result = $this->doSomeStuff($argument);
    $this->writeInTheDB($result);        

    Connections::$dbConn->CompleteTrans();
    
    return $result;
}
```

At first glance it looks OK, however there is a problem. If the method doSomeStuff() throws an exception, CompleteTrans() will not be called. Depending on how your database is configured, this may cause a table to be locked for too longs. If there are other processes trying to write to this table, it will not be possible. This is what was happening with a code of ours in production.

The solution is to wrap the whole transaction in a try/catch statement. In case of an exception, the catch block makes sure sure that the failed transaction is handled properly:  

 ```php
 public function someMethod($argument) {
    
     try {
        Connections::$dbConn->StartTrans();
             
         $result = $this->doSomeStuff($argument);
         $this->writeInTheDB($result);        
         
         Connections::$dbConn->CompleteTrans();
             
         return $result;
     } catch(Exception $exception) {
         Connections::$dbConn->FailTrans();
         Connections::$dbConn->CompleteTrans();
         return $exception->getMessage();   
     }         
}
 ```
 

Usage
=====
Recursively scan directory with php files:

```bash
php runChecks.php -d directory_with_php_files
```

or scan a single file:

```bash
php runChecks.php -f single_file.php
```


Tests
=====
The tests are located in [tests](https://github.com/emanuil/php-unlocker/tree/master/tests) directory. To run them, once in tests directory, type:
```bash
phpunit AdoDBTransactionsTests.php
```
If you extend this tool, make sure that the tests are passing before submitting pull request. Better yet, add new test files and unit tests. 

Continuous Integration
======================
PHP-Unlocker is CI friendly. On error it will exit with -1 status, so it's easy to hook it to your CI jobs.

PHP Parser
==========
PHP-Unlocker is using the excellent php parser with the same name: [PHP-Parser](https://github.com/nikic/PHP-Parser).
