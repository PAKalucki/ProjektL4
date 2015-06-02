<?php
include_once("model/model.php"); 

class kontakt_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
		switch ($this->getAction()) {
			case 'mail':
				$this->mail();
			break;
			case 'wiadomosc':
				$this->wiadomosc();
			break;
			case 'przypomnienie':
				$this->przypomnienie();
			break;
		    default:
		    	$this->show();
		}
	}
	
	public function show()
	{
		include_once("view/kontakt.phtml");
	}
	
	public function mail()
	{
		if(isset($_POST['wyslij_wiadomosc']))
		{
			if($_POST['temat'] == "" || $_POST['wiadomosc'] == "") 
			{
				$this->redirect("index.php?url=kontakt", "success", "Wpisz temat oraz tresc wiadomosci.");
			}
			else
			{
				$result = $this->sql_query("SELECT * FROM klient WHERE ID_klienta = ".$this->getLoggedClientId()."");
				$to      = 'sklep-intern@wp.pl';
				$subject = $_POST['temat'];
				$message = $_POST['wiadomosc'];
				$headers = 'From: '.$result[0]['email'].'' . "\r\n" .
					'Reply-To: '.$result[0]['email'].'' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

				mail($to, $subject, $message, $headers);
			}
		}
	}
	
	public function wiadomosc()
	{
		if($this->isAdmin())
		{
			if(!isset($_POST['wyslij_wiadomosc']))
			{
				$check = false;
				$result2 = $this->sql_query("SELECT * FROM klient");
				if(count($result2[0]) > 0)
				{		
					$result = $this->sql_query("SELECT * FROM klient");
					$check = true;
				}
				include "/../view/kontakt_wiadomosc.phtml";
			}
			else if(isset($_POST['wyslij_wiadomosc']))
			{
				$to      = $_POST['adresat'];
				$subject = $_POST['temat'];
				$message = $_POST['wiadomosc'];
				$headers = 'From: sklep-intern@wp.pl' . "\r\n" .
					'Reply-To: sklep-intern@wp.pl' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

				mail($to, $subject, $message, $headers);
			}
		}
		else
			$this->redirect("index.php?url=kontakt", "success", "Nie masz uprawniec aby przebywac na tej stronie.");
	}
	
	public function przypomnienie()
	{
		if($this->isAdmin())
		{
			if(!isset($_POST['wyslij_wiadomosc']))
			{
				$check = false;
				$date = date('Y-m-d', time());
				$checkedDate = strtotime("-7 days", strtotime($date));
				$result2 = $this->sql_query("SELECT * FROM zamowienie WHERE data_wystawienia < ".$checkedDate." AND status = 'p'");				
				
				if(count($result2[0]) > 0)
				{	
					$result = $this->sql_query("SELECT * FROM zamowienie z, klient k WHERE z.data_wystawienia < ".$checkedDate." AND z.status = 'p' AND z.KLIENT_ID_klienta = k.ID_klienta");
					$check = true;
				}
				include "/../view/kontakt_przypomnienie.phtml";
			}
			else if(isset($_POST['wyslij_wiadomosc']))
			{
				$to      = $_POST['adresat'];
				$subject = $_POST['temat'];
				$message = $_POST['wiadomosc'];
				$headers = 'From: sklep-intern@wp.pl' . "\r\n" .
					'Reply-To: sklep-intern@wp.pl' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();

				mail($to, $subject, $message, $headers);
			}
		}
		else
			$this->redirect("index.php?url=kontakt", "success", "Nie masz uprawniec aby przebywac na tej stronie.");
	}

	
	
}
?>