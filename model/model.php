<?php

abstract class Model extends Controller
{
	protected $mysql;
	
	public function __construct()
	{
		parent::__construct();
		$this->connectDatabase();
		
		include 'view/header.phtml';
	}
	
	public function __destruct()
	{
		include __DIR__ . '/../view/footer.phtml';
	}
	
	public function sql_query($query) /*juz niepotrzebna ~przemek*/
	{
		$ret = array();
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result, MYSQL_BOTH))
			$ret[] = $row;
		
		if (!$ret)
			return false; 
		
		mysql_free_result($result);
		
		return $ret;
	}
	
	public function isLogged()
	{	
		return !empty($_SESSION['logged']);
	}
	
	public function getLoggedAdminId()
	{
	    return isset($_SESSION['id_pracownika']) ? $_SESSION['id_pracownika'] : null;
	}
	
	public function getLoggedClientId()
	{
	    return isset($_SESSION['id_klienta']) ? $_SESSION['id_klienta'] : null;
	}
	
	public function isClient()
	{
		if($this->isLogged() and isset($_SESSION['id_klienta']))
		{
			$client = $this->sql_query("SELECT * FROM `klient` WHERE `ID_klienta`='".$_SESSION['id_klienta']."'");
			if($client)
				return true;
		}
		return false;
	}
	
	public function isAdmin()
	{
		if($this->isLogged() and isset($_SESSION['id_pracownika']))
		{
			$admin = $this->sql_query("SELECT * FROM `pracownik` WHERE `ID_pracownika`='".$_SESSION['id_pracownika']."'");
			if($admin)
				return true;
		}
		return false;
	}
		
	private function connectDatabase()
	{
		require_once('configuration.php');
		$this->mysql = @mysql_connect($DBASE['host'], $DBASE['username'], $DBASE['password']);
		mysql_set_charset('utf8');
	
		if($this->mysql)
		{
			mysql_select_db($DBASE['name']);
	
			if(mysql_error())
				throw new Exception('Nie można odnaleźć bazy '.$DBASE['name']);
		}
		else
			throw new Exception('Nie można połączyć się z bazą danych.');
	}
}
?>
