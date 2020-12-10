<?php
require('xout.php');

//Creating a handle...
$handle = fopen('README.md','r');
$handleClosed = fopen('README.md','r');
fclose($handleClosed);

//Defining an exaple var
$var = 
[
    'cars' => 
    [
        'audi',
        'bmw'
    ], 
    'nothing' => (object)
    [
        'name' => 'Mario', 
        'age' => 34
    ],
    'even more' => (object)
    [
        'price' => 25.99,
        'discount' => -5.25,
        'currentDatetime' => new DateTime(),
        'someObject' => new stdClass(),
        'empty' => null,
        'emptyText' => '',
        'isOkay' => TRUE,
        'isNotOkay' => FALSE,
        'fileHandle' => $handle,
        'fileHandleClosed' => $handleClosed
    ]
];

//Calling xout()
xout($var, true);

fclose($handle);