<?php
require '../xout.php';

class Train
{
    public $description = 'Shinkansen';
    public function activate(){}
}

$something = 
[
    'fruits' => ['apple', 'banana', 'strawberry'],
    'person' => 
    [
        'name' => 'Mario', 
        'age' => 34, 
        'rating' => 7.34, 
        'registered' => false,
        'picture' => null
    ],
    'car' => (object)
    [
        'brand' => 'BWM',
        'hp' => 220.25,
        'drive' => function(){}
    ], 
    'train object' => new Train(),
    'emtpy array' => [],
    'empty object' => (object)[],
    'nested array' => [[[[]]]],
];

xout($something);  