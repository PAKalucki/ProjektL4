<?php
include_once("model/model.php"); 

class uzytkownik_Model extends Model 
{
	public $zarzadzaj = false;

	public function __construct()
	{
		parent::__construct();
		
		if (!$this->isLogged())
		    $this->redirect($this->generateUrl(), "error", "Zaloguj sie aby moc korzystac z tej funkcji sklepu.");
		
		switch ($this->getAction()) {
			case 'edytuj':
				$this->zarzadzaj = $this->edytuj();
			    include_once("view/uzytkownik_edytuj.phtml");
				break;
			case 'usun':
				$this->zarzadzaj = $this->usun();
				include_once("view/uzytkownik_usun.phtml");
				break;
		    default:
		    	$this->pokaz();
		}
	}
	public function pokaz()
	{
		if($this->isAdmin())
		{
			$check = false;
			$result2 = $this->sql_query("SELECT * FROM `klient`");
			if(count($result2[0]) > 0)
			{
				$result = $this->sql_query("SELECT * FROM `klient`");
				$check = true;
			}
			include "/../view/uzytkownik.phtml";
		}
	}
	public function usun()
	{
		if($this->isAdmin())
		{
			if(isset($_GET['id']))
			{
				if(isset($_POST['usun']))
				{
					$query1 = mysql_query("SELECT * FROM `zamowienie` WHERE `KLIENT_ID_klienta`='".addslashes($_GET['id'])."'"); 
					while($x = mysql_fetch_array($query1))
					{
						mysql_query("DELETE FROM `pozycja_zamowienia` WHERE `ZAMOWIENIE_ID_zamowienia` = '".$x['ID_zamowienia']."'");
						mysql_query("DELETE FROM `zamowienie` WHERE `KLIENT_ID_klienta` = ".$_GET['id']."");
					}
					mysql_query("DELETE FROM `klient` WHERE `ID_klienta` = ".$_GET['id']."");
					$this->redirect("index.php?url=uzytkownik", "error", "Klient zostal poprawnie usuniety z bazy danych.");
				}	
			}
		}
		else
		{
			if(isset($_POST['usun']))
			{
				$query1 = mysql_query("SELECT * FROM `zamowienie` WHERE `KLIENT_ID_klienta`='".$this->getLoggedClientId()."'"); 
				while($x = mysql_fetch_array($query1))
				{
					mysql_query("DELETE FROM `pozycja_zamowienia` WHERE `ZAMOWIENIE_ID_zamowienia` = '".$x['ID_zamowienia']."'");
					mysql_query("DELETE FROM `zamowienie` WHERE `KLIENT_ID_klienta` = ".$this->getLoggedClientId()."");
				}
				mysql_query("DELETE FROM `klient` WHERE `ID_klienta` = ".$this->getLoggedClientId()."");
				unset($_SESSION['koszyk']);
				session_unset(); 
				$this->redirect("index.php", "error", "Twoje konto zostalo usuniete z bazy danych.");
			}
		}	
	}	
	public function edytuj()
	{
		if($this->isAdmin())
		{
			if(isset($_GET['id']))
			{
				if(!isset($_POST['edytuj']))
				{
					$result = $this->sql_query("SELECT * FROM `klient` WHERE `ID_klienta` = '".addslashes($_GET['id'])."' LIMIT 1");
					if($result)
					{
						return $result[0];
					}
				}
				else if(isset($_POST['edytuj']))
				{
					if($_POST['login'] == '' || $_POST['haslo'] == '' || $_POST['imie'] == '' || $_POST['nazwisko'] == '' || $_POST['miasto'] == '' || $_POST['kod_pocztowy'] == '' || $_POST['ulica'] == '' || $_POST['nr_domu'] == '' || $_POST['email'] == '')
						$this->redirect("index.php?url=uzytkownik", "error", "Wprowadz wszystkie dane poprawnie.");
					else if($_POST['login'] == '' || $_POST['haslo'] == '')
						$this->redirect("index.php?url=uzytkownik", "error", "Wprowadz poprawnie login i haslo.");
					else
					{
						mysql_query("UPDATE klient SET imie = '".$_POST['imie']."', nazwisko = '".$_POST['nazwisko']."', miasto = '".$_POST['miasto']."', kod_pocztowy = '".$_POST['kod_pocztowy']."', ulica = '".$_POST['ulica']."', nr_domu = '".$_POST['nr_domu']."', nr_lokalu = '".$_POST['nr_lokalu']."', telefon = '".$_POST['telefon']."', email = '".$_POST['email']."', login = '".$_POST['login']."', haslo = '".$_POST['haslo']."', data_modyfikacji = '".time()."' WHERE ID_klienta = '".addslashes($_GET['id'])."'"); 
						$this->redirect("index.php?url=uzytkownik", "error", "Konto poprawnie zaktualizowane.");
					}
				}
			}
		}
		else
		{
			if(!isset($_POST['edytuj']))
			{
				$result = $this->sql_query("SELECT * FROM `klient` WHERE `ID_klienta` = '".$this->getLoggedClientId()."' LIMIT 1");
				if($result)
				{
					return $result[0];
				}
			}
			else if(isset($_POST['edytuj']))
			{
				if($_POST['login'] == '' || $_POST['haslo'] == '' || $_POST['imie'] == '' || $_POST['nazwisko'] == '' || $_POST['miasto'] == '' || $_POST['kod_pocztowy'] == '' || $_POST['ulica'] == '' || $_POST['nr_domu'] == '' || $_POST['email'] == '')
					$this->redirect("index.php?url=profil", "error", "Wprowadz wszystkie dane poprawnie.");
				else if($_POST['login'] == '' || $_POST['haslo'] == '')
					$this->redirect("index.php?url=profil", "error", "Wprowadz poprawnie login i haslo.");
				else
				{
					mysql_query("UPDATE klient SET imie = '".$_POST['imie']."', nazwisko = '".$_POST['nazwisko']."', miasto = '".$_POST['miasto']."', kod_pocztowy = '".$_POST['kod_pocztowy']."', ulica = '".$_POST['ulica']."', nr_domu = '".$_POST['nr_domu']."', nr_lokalu = '".$_POST['nr_lokalu']."', telefon = '".$_POST['telefon']."', email = '".$_POST['email']."', login = '".$_POST['login']."', haslo = '".$_POST['haslo']."', data_modyfikacji = '".time()."' WHERE ID_klienta = '".$this->getLoggedClientId()."'"); 
					$this->redirect("index.php?url=profil", "error", "Konto poprawnie zaktualizowane.");
				}
			}
		}	
	}
}
?>