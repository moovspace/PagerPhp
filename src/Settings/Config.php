<?php
namespace Pager\Settings;

class Config
{
	// Company email
	const CONTACT_EMAIL = 'usero@local.host';
	// Currency
	const CURRENCY = 'PLN';
	// Blog perpage
	const PERPAGE = 5;
	// Shop perpage
	const PERPAGE_SHOP = 25;

	// Mysql db
	const MYSQL_HOST = 'localhost';
	const MYSQL_USER = 'delivery';
	const MYSQL_PASS = 'toor';
	const MYSQL_PORT = 3306;
	const MYSQL_DBNAME = 'delivery';

	// Smtp settings
	const SMTP_USER = '';
	const SMTP_PASS = '';
	const SMTP_HOST = '127.0.0.1';
	const SMTP_PORT = 25; // 25, 587, 465
	const SMTP_FROM_EMAIL = 'no-reply@local.host';
	const SMTP_FROM_USER = 'Welcome';
	const SMTP_TLS = false;
	const SMTP_AUTH = false;
	const SMTP_DEBUG = 0;

	// Allowed files extension
	const FILES_EXTENSIONS = ['jpg', 'png', 'gif', 'jpeg', 'zip', 'mp4', 'ogg'];
}
?>
