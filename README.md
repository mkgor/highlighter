# Highlighter
Library, which allows you to highlight your PHP code in terminal

## Installation
Installation via composer:

````
composer require mkgor/highlighter
````

## Printing whole file
````php
<?php

require_once "vendor/autoload.php";

$highlighter = new \Highlighter\Highlighter();

echo $highlighter->getWholeFile(__FILE__);
````

#### Result
![Result](https://i.imgur.com/TC1mP2u.png)

## Printing code snippet (and highlighting specified line)
````php
<?php

require_once "vendor/autoload.php";

$highlighter = new \Highlighter\Highlighter();

echo $highlighter->getLineWithNeighbors(__FILE__, 3);
````

#### Result

![Result](https://i.imgur.com/iqEfh0d.png)


## Printing single line
````php
<?php

require_once "vendor/autoload.php";

$highlighter = new \Highlighter\Highlighter();

echo $highlighter->getLine(__FILE__, 3);
````
#### Result

![Result](https://i.imgur.com/qfJWGrP.png)