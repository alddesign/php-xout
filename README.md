# php-xout
A more readable, syntax highlighted implementation of PHPs `var_dump()` or `print_r()` function.  
*Compatible with all PHP versions **back to 5.4***

## Installation
### Via Composer
`composer require alddesign/php-xout`
### Manual
Downlaod the package and load `xout.php`: 
```php 
require_once 'xout.php'
```

## Usage
```php
//code before...
$array = 
[
    'cars' => ['audi','bmw','volkswagen'], 
    'settings' => (object)
    [
        'drive' => true, 
        'disable_car' => 
        function(){}
    ],
    'value' => 220.25,
    'active' => null
];

//Call xout
Xout::xout($array);

//Or use the shorthand function:
xout($var);
```

The output will look like this:  
![output](<test/output.png>)

## Remarks
### Parameters
- **value**: The expression to output
- **return** (bool): If set to `true` the resulting html is being returned insted of echoing it. Default: `false`
- **dontDie** (bool): If set to `true` the script will not terminated. When `return` is set to `true`, this parameter has no effect. Default: `false`

### Customization
In `xout.php` you can change many options like font, color, brace style,...