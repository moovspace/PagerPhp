# PagerPhp
Php pagination links class. Links with icons.

### PagerPhp Links
<img src="https://raw.githubusercontent.com/moovspace/PagerPhp/master/pagerphp.png" width="100%">

### How to
```php
<!-- Add fontawesome 5 for icons in links -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css">

### Composer import
```json
...
"require": {
	"moovspace/pagerphp": "3.1"
}
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

// Import
use PagerPhp\Pager;
use PagerPhp\Mysql\Db;

// Pager init
$p = new Pager();
// Error page
$p->ErrorPage('Wrong page number!');
// Min perpage
$p->Perpage(4);
// Table max rows
$max_rows = $p->GetMaxRows();
// Create links count max page
$links = $p->Links((int) $_GET['page'], $max_rows, (int) $_GET['perpage']); // Create pagination links
// Then get rows
$rows = $p->GetRows();
// Next style links
$style = $p->Style();
?>
```

### Custom pager class
```php
<?php
use PagerPhp\Pager;
use PagerPhp\Mysql\Db;

class CategoryList extends Pager
{
	function GetMaxRows()
	{
		$arr = [];
		$sql = "SELECT COUNT(*) as cnt FROM category";
		return Db::Query($sql,$arr)->FetchAll()[0]['cnt'];
	}

	function GetRows($page = 1, $offset = 0, $perpage = 1)
	{
		if(!empty($_GET['page'])) { $page = (int)$_GET['page']; }
		if(!empty($_GET['perpage'])) { $perpage = (int)$_GET['perpage']; }
		if($perpage < $this->MinPerpage) { $perpage = $this->MinPerpage; }

		$arr = [
			':offset' => self::Offset((int) $page, (int) $perpage),
			':perpage' => $perpage
		];
		$sql = "SELECT * FROM category ORDER BY name ASC LIMIT :offset,:perpage";
		return Db::Query($sql,$arr)->FetchAllObj();
	}
}

$c = new CategoryList();
$c->Perpage(4);
$max_rows = $c->GetMaxRows();
$links = $c->Links((int) $_GET['page'], $max_rows, (int) $_GET['perpage']); // Create pagination links
$rows = $c->GetRows();

echo $links;
print_r($rows);
```

### Hide php warnings, notice
```php
// Errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// Hide warnings and notice
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
```