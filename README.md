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

echo $highlighter->getSnippet(__FILE__, 3);
````

#### Result

![Result](https://i.imgur.com/iqEfh0d.png)

## Printing code snippet with specified lines
````php
<?php

require_once "vendor/autoload.php";

$highlighter = new \Highlighter\Highlighter();

echo $highlighter->getSpecifiedSnippet(__FILE__, 3, 5);
````

#### Result

![Result](https://i.imgur.com/uuajqgQ.png)

## Printing single line
````php
<?php

require_once "vendor/autoload.php";

$highlighter = new \Highlighter\Highlighter();

echo $highlighter->getLine(__FILE__, 3);
````
#### Result

![Result](https://i.imgur.com/qfJWGrP.png)


##
You can use same functions with suffix `WithoutNumbers` to highlight code without line numbers:

```php
<?php

require_once "vendor/autoload.php";

$highlighter = new \Highlighter\Highlighter();

echo $highlighter->getWholeFileWithoutNumbers(__FILE__);
```

#### Result

![Result](https://i.imgur.com/F7Mo4Rq.png)