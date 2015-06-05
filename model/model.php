<?php
date_default_timezone_set('Europe/Warsaw');
abstract class ModelClass extends Controller
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
			#$client = $this->sql_query("SELECT * FROM `klient` WHERE `ID_klienta`='".$_SESSION['id_klienta']."'");
                        
                        if(klient::findKlient($_SESSION['id_klienta']))
                        {
				return true;
                        }

		}
		return false;
	}
	
	public function isAdmin()
	{
		if($this->isLogged() and isset($_SESSION['id_pracownika']))
		{
			#$admin = $this->sql_query("SELECT * FROM `pracownik` WHERE `ID_pracownika`='".$_SESSION['id_pracownika']."'");
			if($admin=pracownik::findPracownik($_SESSION['id_pracownika']))
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
	
	public function sendMail($email, $subject, $body)
	{
		require("configuration.php");
		require("controller/phpmailer/class.phpmailer.php");
		
		$mail = new PHPMailer();
		$mail->PluginDir = "controller/phpmailer/";
		$mail->From = $MAIL['address'];
		$mail->FromName = "Sklep-intern";
		$mail->Host = "smtp.wp.pl";
		$mail->Port = 465;
		$mail->Mailer = "smtp";
		$mail->Username = "sklep-intern"; 
		$mail->Password = "projekt2015"; 
		$mail->SMTPAuth = true;
		$mail->SetLanguage("pl", "phpmailer/language/");
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->AddAddress($email,"Klient");

		if(!$mail->Send())
			echo $mail->ErrorInfo."<br>";

		$mail->ClearAddresses();
		$mail->ClearAttachments();
	}
        

}
?>
