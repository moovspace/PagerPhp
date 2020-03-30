# PagerPhp
Php pagination links class. Links with icons.

### PagerPhp Links
<img src="https://raw.githubusercontent.com/moovspace/PagerPhp/master/pagerphp.png" width="100%">

### How to
```php
<!-- Add fontawesome 5 for icons in links -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">

<?php
// Only pager
require('Pager.php');

$o = new Pager();
echo $o->Links((int) $_GET['page'], 123, (int) $_GET['perpage']);
echo $o->Style();
?>
```

### Composer import
```json
...
"require": {
        "moovspace/PagerPhp": "v2.0"
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/moovspace/PagerPhp"
    }
]
...
```
### Update composer
```bash
composer update
composer dump-autoload -o
```

### Composer how to
```php
<?php
require('vendor/autoload.php');

use PagerPhp\Pager;

$o = new Pager();
echo $o->Links((int) $_GET['page'], 123, (int) $_GET['perpage']);
echo $o->Style();
?>
```
### Pager sample PDO
https://github.com/moovspace/PagerPhp/blob/master/pager-pdo-sample.php


### Hide php warnings, notice
```php
// Errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// Hide warnings and notice
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
```
