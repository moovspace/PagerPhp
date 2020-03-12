# PagerPhp
Php pagination links class. Links with icons.

### PagerPhp Links
<img src="https://raw.githubusercontent.com/moovspace/PagerPhp/master/pagerphp.png" width="100%">

### How to
```php
<!-- Add fontawesome 5 for icons in links -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">

<?php
require('Pager.php');

$o = new Pager();
echo $o->Links((int) $_GET['page'], 123, (int) $_GET['perpage']);
echo $o->Style();
?>
```
