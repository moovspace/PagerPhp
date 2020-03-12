# PagerPhp
Php pagination links class. Links with icons.

### PagerPhp Links
<img src="https://raw.githubusercontent.com/moovspace/PagerPhp/master/pager-php.png" width="100%">

### Hoe to
```php
<?php
require('Pager.php');

$o = new Pager();
echo $o->Links((int) $_GET['page'], 123, $_GET['perpage']);
echo $o->Style();
?>
```
