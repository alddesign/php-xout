# php-xout
A more readable implementation of PHP´s var_dump() function

## Usage
```php
require('xout.php');

$var = ['cars' => ['audi','bmw'], 'nothing' => (object)['name' => 'Mario', 'age' => 34]];
xout($var);
```
This will output the contents of <code>$var</code> to the browser and call <code>die();</code> (Unless you specify <code>$dontDie = true</code>)

The output will look like:

![output](<out.png>)
