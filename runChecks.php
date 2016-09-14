<?php

require 'AdoDBTransactionsChecks.php';

$options = getopt("d:f:");

if(empty($options)) {
    echo "Usage: runChecks -d directory -f file\n";
    echo "-d directory: the directory to check\n";
    echo "-f directory: the file to check\n";
    echo "\n";

    exit(-1);
}

$checks = new AdoDBTransactionsChecks();

if(isset($options['d'])) {
    $checks->checkDirectory($options['d']);
}

if(isset($options['f'])) {
    $checks->checkSingleFile($options['f']);
}

