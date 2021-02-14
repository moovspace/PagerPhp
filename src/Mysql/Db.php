<?php
declare(strict_types=1);
namespace PagerPhp\Mysql;

use PDO;
use Exception;
use Redis;

/**
 * Db - Mysql class
 */
class Db
{
	public static $Pdo = null;
	protected static $Stm = null;
	// Mysql
	protected static $MYSQL_DBNAME = 'app';
	protected static $MYSQL_HOST = 'localhost';
	protected static $MYSQL_USER = 'root';
	protected static $MYSQL_PASS = 'toor';
	protected static $MYSQL_PORT = 3306;
	// Redis
	protected static $REDIS_PASS = '';
	protected static $REDIS_HOST = 'localhost';
	protected static $REDIS_PORT = 6379;
	protected static $REDIS_TTL = 600;

	public static function GetInstance(): self
	{
		static $instance;

		if (null === $instance) {
			$instance = new self();
		}

		return $instance;
	}

	protected function __construct()
	{
		self::$Pdo = self::Conn();
	}

	protected function __clone()
	{
	}

	protected function __wakeup()
	{
	}

	final static function Host($host)
	{
		self::GetInstance();
		self::$MYSQL_HOST = $host;
		return self::GetInstance();
	}

	final static function Port($port)
	{
		self::GetInstance();
		self::$MYSQL_PORT = (int) $port;
		return self::GetInstance();
	}

	final static function Database($name)
	{
		self::GetInstance();
		self::$MYSQL_DBNAME = $name;
		return self::GetInstance();
	}

	final static function User($name)
	{
		self::GetInstance();
		self::$MYSQL_USER = $name;
		return self::GetInstance();
	}

	final static function Pass($pass)
	{
		self::GetInstance();
		self::$MYSQL_PASS = $pass;
		return self::GetInstance();
	}

	final static function RedisTTL($sec)
	{
		self::GetInstance();
		self::$REDIS_TTL = (int) $sec;
		return self::GetInstance();
	}

	final static function RedisHost($host)
	{
		self::GetInstance();
		self::$REDIS_HOST = $host;
		return self::GetInstance();
	}

	final static function RedisPort($port)
	{
		self::GetInstance();
		self::$REDIS_PORT = (int) $port;
		return self::GetInstance();
	}

	final static function RedisPass($pass)
	{
		self::GetInstance();
		self::$REDIS_PASS = $pass;
		return self::GetInstance();
	}

	final static function Conn(){
		try
		{
			// pdo
			$con = new PDO('mysql:host='.self::$MYSQL_HOST.';port='.self::$MYSQL_PORT.';dbname='.self::$MYSQL_DBNAME.';charset=utf8mb4', self::$MYSQL_USER, self::$MYSQL_PASS);
			// show warning text
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			// throw error exception
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// default fetch mode
			$con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			// don't colose connecion on script end
			$con->setAttribute(PDO::ATTR_PERSISTENT, true);
			// set utf for connection utf8_general_ci or utf8_unicode_ci
			$con->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
			// prepared statements, don't cache query with prepared statments
			$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			// auto commit
			// $con->setAttribute(PDO::ATTR_AUTOCOMMIT,flase);
			// buffered querry default
			// $con->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true);
			return $con;
		}
		catch(Exception $e)
		{
			echo 'ERR_CONN: ' . $e->getMessage ();
			// print_r($e->errorInfo());
			return null;
		}
	}

	/**
	 * Mysql query
	 *
	 * $row = Db::Query("SELECT * FROM user WHERE id = :id", [':id' => 123456])->FetchObj();
	 * $rows = Db::Query("SELECT * FROM user WHERE id > :id", [':id' => 0])->FetchAll();
	 *
	 * @param string $sql Mysql query
	 * @param array $arr Array with params for secure sql injection
	 * @return object Return self object
	 */
	static function Query($sql, $arr = array())
	{
		self::GetInstance();
		self::$Stm = self::$Pdo->prepare($sql);
		self::$Stm->execute($arr);

		return self::GetInstance();
	}

	/**
	 * Fetch table row
	 *
	 * @return array Table row array
	 */
	function Fetch()
	{
		return self::$Stm->fetch();
	}

	/**
	 * Fetch table row
	 *
	 * @return object Table row array
	 */
	function FetchObj()
	{
		return self::$Stm->fetch(PDO::FETCH_OBJ);
	}

	/**
	 * Fetch table rows
	 *
	 * @return array Table rows array
	 */
	function FetchAll()
	{
		return self::$Stm->fetchAll();
	}

	/**
	 * Fetch table rows
	 *
	 * @return array Table rows array
	 */
	function FetchAllObj()
	{
		return self::$Stm->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Count table rows
	 *
	 * @return int Number
	 */
	function RowCount()
	{
		return self::$Stm->rowCount();
	}

	/**
	 * Last insert record id
	 *
	 * @return int Number
	 */
	function LastInsertId()
	{
		return self::$Pdo->lastInsertId();
	}

	/**
	 * Get data from db or redis cache if exists in it
	 *
	 * @param string $sql Mysql Query
	 * @param array $arr Pdo params array with [':val' => 'string']
	 * @return mixed Array with objects from cache or db
	 */
	static function QueryCache(string $sql, array $arr, $fetchSingleRow = false)
	{
		$port = (int) self::$REDIS_PORT;
		$host = (string) self::$REDIS_HOST;
		$pass = (string) self::$REDIS_PASS;
		$ttl = (int) self::$REDIS_TTL;

		if(empty($host)) { $host = 'localhost'; }
		if(empty($port)) { $port = 6379; }


		$re = new Redis();
		$re->connect($host, $port);
		if(!empty($pass)) {
			$re->auth($pass);
		}

		$key = md5($sql.serialize($arr));
		if($re->exists($key)) {
			return unserialize($re->get($key));
		}

		if($fetchSingleRow) {
			$rows =  self::Query($sql,$arr)->FetchObj();
		} else {
			$rows =  self::Query($sql,$arr)->FetchAllObj();
		}

		$re->set($key, serialize($rows), $ttl);

		return $rows;
	}
}