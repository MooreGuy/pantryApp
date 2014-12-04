<?php
class Database
{
	private static $dbName = 'pantry';
	private static $dbHost = 'localhost';
	private static $dbUsername = 'gmoore';
	private static $dbUserPassword = 'pN0neofyourbeeswax16';
	
	private static $cont = null;

	public function __construct() 
	{
		die('Init function is not allowed');

	}
	
	public static function connect()
	{

		//Use only one connection
		if( self::$cont == null )
		{
			try
			{	
				self::$cont = new PDO( "mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword);
			}
			catch (PDOException $e)
			{
				die($e->getMessage());
			}
		}

		return self::$cont;
	}

	public static function disconnect()
	{
		self::$cont = null;
	}
}
?>

